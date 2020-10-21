<?php

require_once './src/Database.php';

use App\Database;

require_once 'vendor/autoload.php';

if (file_exists('.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

$db = new Database();

Flight::route('/api/post-data', function () use ($db) {
    header('content-type: application/json');
    if (hash_equals($_GET['API_TOKEN'], $_ENV['API_TOKEN'])) {
        $query = $db->getConnection()->prepare("INSERT INTO decibels(mac_id, sound) VALUES(?,?)");
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

Flight::start();
