<?php


namespace App\Services;


class Config
{

    public const APP_DIR = 'app';
    public const CONFIG_DIR = 'config';
    public const PUBLIC_DIR = 'public';

    protected const APP_CONFIG_FILE = 'app.json';

    protected static $config = [];

    public static function get($name, $default = '')
    {
        return self::$config[$name] ?? $default;
    }

    public static function set($name, $value)
    {
        self::$config[$name] = $value;
    }

    protected static function getConfigPath()
    {
        return ROOT_DIR . DS . self::CONFIG_DIR . DS . self::APP_CONFIG_FILE;
    }

    /**
     * Save config data to a json file
     *
     * @return string|false
     */
    public static function save()
    {
        return file_put_contents(
            self::getConfigPath(),
            json_encode(self::$config),
            LOCK_EX
        );
    }

    /**
     * Loads config data from a json file
     *
     * @return string|false
     */
    public static function load()
    {
        $rawFile = file_get_contents(self::getConfigPath());
        if ($rawFile) {
            self::$config = json_decode($rawFile, true);
        }

        return $rawFile;
    }
}