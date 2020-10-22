<?php

namespace App;

use PDO;

class Auth
{

    public static function verify($db, $username, $password)
    {
        $query = $db->prepare("SELECT count(*) as count FROM users WHERE username = ? AND password = ?");
        $query->execute([$username, $password]);
        $result = $query->fetch(PDO::FETCH_OBJ);
        return !empty($result->count);
    }

    public static function verifyAPI($db, $mac_id, $token)
    {
        $query = $db->prepare("SELECT api_key FROM users WHERE id = (SELECT user_id FROM nodes WHERE mac_id = ?)");
        $query->execute([$mac_id]);
        $result = $query->fetch(PDO::FETCH_OBJ);
        return !empty($token) && !empty($result->api_key) && hash_equals($token, $result->api_key);
    }

    public static function isLoggedIn()
    {
        if (!empty($_SESSION['isLoggedIn'])) {
            return $_SESSION['isLoggedIn'];
        }
        return false;
    }

    public static function setLoggedIn($username)
    {
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['username'] = $username;
    }

    public static function logout()
    {
        unset($_SESSION['username']);
        $_SESSION['isLoggedIn'] = false;
    }

    public static function getUsername()
    {
        if (!empty($_SESSION['username'])) {
            return $_SESSION['username'];
        }
        return false;
    }

    public static function getCurrentUserID($db)
    {
        $username = self::getUsername();
        $query = $db->prepare("SELECT id FROM users WHERE username = ?");
        $query->execute([$username]);
        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result->id;
    }

    public static function getAPIKey($db, $username)
    {
        $username = self::getUsername();
        $query = $db->prepare("SELECT api_key FROM users WHERE username = ?");
        $query->execute([$username]);
        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result->api_key;
    }
}
