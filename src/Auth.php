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
}
