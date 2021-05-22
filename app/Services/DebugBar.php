<?php


namespace App\Services;

final class DebugBar
{
    protected static $debugBar;

    public static function boot($debugBar)
    {
        self::$debugBar = new $debugBar();

        return self::$debugBar;
    }

    public static function get($key = null)
    {
        return $key ? self::$debugBar[$key] : self::$debugBar;
    }

}
