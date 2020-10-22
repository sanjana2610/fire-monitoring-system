<?php

session_start();

require_once 'vendor/autoload.php';
require_once './src/Database.php';
require_once './src/Auth.php';

use App\Auth;
use App\Database;
use Carbon\Carbon;

if (file_exists('.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

$db = new Database();
$db = $db->getConnection();

Flight::route('POST /api/add-sound-level', function () use ($db) {
    $data = $_POST;
    if (Auth::verifyAPI($db, $data['mac_id'], $data['token'])) {
        $query = $db->prepare("INSERT INTO decibels(mac_id, sound) VALUES(?,?)");
        $query->execute([
            htmlspecialchars($data['mac_id'], ENT_QUOTES),
            htmlspecialchars($data['sound'], ENT_QUOTES)
        ]);
        Flight::json(['message' => 'Decibels posted successfully']);
    } else {
        Flight::json(['message' => 'Authentication failed'], 401);
    }
});

Flight::route('/', function () use ($db) {
    if (!Auth::isLoggedIn()) {
        Flight::redirect('/login');
        return;
    }

    $query = $db->prepare("SELECT * FROM nodes WHERE user_id = ? ORDER BY name");
    $query->execute([Auth::getCurrentUserID($db)]);
    $nodes = $query->fetchAll(PDO::FETCH_OBJ);

    foreach ($nodes as $idx => $node) {
        $query = $db->prepare(
            "SELECT created_at FROM decibels WHERE mac_id = ? ORDER BY created_at DESC LIMIT 1"
        );
        $query->execute([$node->mac_id]);
        $nodes[$idx]->lastUpdated = $query->fetch(PDO::FETCH_OBJ)->created_at;
    }

    Flight::render('dashboard.php', [
        'username' => Auth::getUsername(),
        'api_key' => Auth::getAPIKey($db, Auth::getUsername()),
        'nodes' => $nodes
    ]);
});

Flight::route('GET /new-node', function () use ($db) {
    if (!Auth::isLoggedIn()) {
        Flight::redirect('/login');
        return;
    }

    Flight::render('new-node.php', [
        'username' => Auth::getUsername(),
        'api_key' => Auth::getAPIKey($db, Auth::getUsername()),
    ]);
});

Flight::route('POST /new-node', function () use ($db) {
    if (!Auth::isLoggedIn()) {
        Flight::redirect('/login');
        return;
    }

    $query = $db->prepare("INSERT INTO nodes VALUES(?,?,?)");
    $query->execute([
        htmlentities($_POST['mac_id'], ENT_QUOTES),
        htmlentities($_POST['name'], ENT_QUOTES),
        Auth::getCurrentUserID($db)
    ]);
    Flight::redirect('/');
});

Flight::route('/api/monitor/@id', function ($id) use ($db) {
    if (Auth::verifyViewGraph($db, $id)) {
        $query = $db->prepare(
            "SELECT sound, created_at FROM (
                            SELECT * FROM decibels WHERE mac_id = ? ORDER BY created_at DESC LIMIT 20 
                        ) temp ORDER BY created_at"
        );
        $query->execute([$id]);
        $data = $query->fetchAll(PDO::FETCH_OBJ);

        $response = [];
        foreach ($data as $datum) {
            array_push($response, [
                'x' => Carbon::createFromFormat(
                    "Y-m-d H:i:s",
                    $datum->created_at,
                    'Asia/Kolkata')->timestamp,
                'y' => (int)$datum->sound
            ]);
        }

        Flight::json([
            'data' => $response
        ]);
    } else {
        Flight::json([
            'message' => 'Unauthorized'
        ], 401);
    }
});
Flight::route('/monitor/@id', function ($id) use ($db) {
    if (Auth::verifyViewGraph($db, $id)) {
        $query = $db->prepare(
            "SELECT sound, created_at FROM (
                            SELECT * FROM decibels WHERE mac_id = ? ORDER BY created_at DESC LIMIT 20 
                        ) temp ORDER BY created_at"
        );
        $query->execute([$id]);
        $data = $query->fetchAll(PDO::FETCH_OBJ);

        $query = $db->prepare("SELECT name FROM nodes WHERE mac_id = ?");
        $query->execute([$id]);
        $node_name = $query->fetch(PDO::FETCH_OBJ)->name;

        Flight::render('graph.php', [
            'username' => Auth::getUsername(),
            'api_key' => Auth::getAPIKey($db, Auth::getUsername()),
            'node_name' => $node_name,
            'data' => $data,
            'mac_id' => $id
        ]);
    } else {
        Flight::notFound();
    }
});

Flight::route('/logout', function () {
    Auth::logout();
    Flight::redirect('/');
});

Flight::route('GET /login', function () {
    if (Auth::isLoggedIn()) {
        Flight::redirect('/');
        return;
    }

    Flight::render('login.php');
});

Flight::route('POST /login', function () use ($db) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if (Auth::verify($db, $username, md5($password))) {
        Auth::setLoggedIn($username);
        Flight::redirect('/');
    } else {
        Flight::render('login.php', ['error' => 'Invalid credentials 😢']);
    }
});

Flight::start();
