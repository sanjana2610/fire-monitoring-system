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

Flight::route('/', function () {
    if (!Auth::isLoggedIn()) {
        Flight::redirect('/login');
        return;
    }

    Flight::render('dashboard.php', ['username' => Auth::getUsername()]);
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
