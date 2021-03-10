<?php


namespace App\Services;


class Config
{

    public const APP_DIR = 'app';
    public const CONFIG_DIR = 'config';
    public const PUBLIC_DIR = 'public';

    protected static string $rootPath = '';
    protected static string $configFilename = 'app.json';

    protected static $config = [];

    public function __invoke($filename)
    {

    }

    public static function get($key, $default = null)
    {
         return self::$config[$key] ?? ($default ?: null);
    }

    public static function has($key)
    {
        return isset(self::$config[$key]);
    }

    public static function set($name, $value)
    {
        self::$config[$name] = $value;
    }

    protected static function getConfigPath()
    {
        return self::$rootPath . DIRECTORY_SEPARATOR . self::CONFIG_DIR . DIRECTORY_SEPARATOR . self::$configFilename;
    }

    public static function setRootPath(string $path)
    {
        self::$rootPath = $path;
    }

    public static function setConfigFilename(string $filename)
    {
        self::$configFilename = $filename;
    }

    public static function use($filename)
    {
        self::setConfigFilename($filename);
        return self::load();
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
     * @param string|null $path
     *
     * @return string|false
     */
    public static function load(string $full_path = null)
    {
        $rawFile = file_get_contents($full_path ?: self::getConfigPath());
        if ($rawFile) {
            self::$config = json_decode($rawFile, true);
        }

        return $rawFile;
    }

    public static function clear()
    {
        self::$config = [];
    }
}