<?php


namespace App\Services;

use App\Models\User;

class Auth
{
    protected static $user;

    public static function attempt($login, $password)
    {
        if ( ! empty($login) && ! empty($password)) {
            self::$user = User::getByEmail($login);

            if (Hash::check($password, self::$user->hash)) {
                $_SESSION['isLoggedIn'] = true;
                $_SESSION['role'] = self::$user->role;
                return true;
            }
        }

        return false;
    }

    public static function user()
    {
        return self::$user;
    }

    public static function isAdmin()
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            return true;
        }

        return false;
    }

    public static function isLogged()
    {
        return $_SESSION['isLoggedIn'] ?: false;
    }

}
