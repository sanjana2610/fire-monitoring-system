<?php

session_start();

require_once 'vendor/autoload.php';
require_once './src/Database.php';
require_once './src/Auth.php';

use App\Auth;
use App\Database;

if (file_exists('.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

$db = new Database();
$db = $db->getConnection();

Flight::route('/api/post-data', function () use ($db) {
    header('content-type: application/json');
    if (hash_equals($_GET['API_TOKEN'], $_ENV['API_TOKEN'])) {
        $query = $db->prepare("INSERT INTO decibels(mac_id, sound) VALUES(?,?)");
        $query->execute([
            htmlspecialchars($_GET['MAC_ID'], ENT_QUOTES),
            htmlspecialchars($_GET['SOUND'], ENT_QUOTES)
        ]);
        echo json_encode([
            'message' => 'Decibels posted successfully'
        ]);
    } else {
        echo json_encode([
            'message' => 'API Token error'
        ]);
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
        'nodes' => $nodes
    ]);
});

Flight::route('GET /new-node', function () {
    if (!Auth::isLoggedIn()) {
        Flight::redirect('/login');
        return;
    }

    Flight::render('new-node.php', ['username' => Auth::getUsername()]);
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
