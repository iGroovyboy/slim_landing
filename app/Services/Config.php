<?php


namespace App\Services;


use Symfony\Component\PropertyAccess\PropertyAccess;

class Config
{
    protected static string $configDir = 'config';
    protected static string $configFilename = 'app.json';

    static string $assetsDir = 'assets';

    static $config = [];
    static $propertyAccessor;

    public static function setPropertyAccessor($propertyAccessor)
    {
        self::$propertyAccessor = $propertyAccessor;
    }

    public function __invoke($filename)
    {
        return self::$config;
    }

    public static function get($propertyPath, $default = null)
    {
        $path = self::convertPathToArrayNotation($propertyPath);

        return self::$propertyAccessor->getValue(self::$config, $path) ?: $default;
    }

    public static function getPath($path, $suffix = ''): string
    {
        $path = str_replace(['/', '\\'], DS, Config::get($path));

        $path = Path::replaceKeysInPath( $path, self::get('app/paths') );

        return ROOT_DIR . DS . $path . DS . $suffix;
    }

    public static function set($propertyPath, $value)
    {
        $path = self::convertPathToArrayNotation($propertyPath);

        self::$propertyAccessor->setValue(self::$config, $path, $value);
    }

    /**
     * Converts path like 'app/section/key' to '[app][section][key]'
     *
     * @param $propertyPath
     *
     * @return string
     */
    protected static function convertPathToArrayNotation($propertyPath): string
    {
        return '[' . str_replace('/', '][', $propertyPath) . ']';
    }

    public static function has($propertyPath)
    {
        return self::$propertyAccessor->getValue(self::$config, $propertyPath);
    }

    protected static function getConfigPath()
    {
        return ROOT_DIR . DIRECTORY_SEPARATOR . self::$configDir . DIRECTORY_SEPARATOR;
    }

    public static function setConfigDir(string $dir)
    {
        self::$configDir = $dir;
    }

    public static function setConfigFilename(string $filename)
    {
        self::$configFilename = $filename;
    }

    public static function use($filename)
    {
        self::setConfigFilename($filename);

        return self::load($filename);
    }

    /**
     * Save config data to json files
     *
     * @return string|false
     */
    public static function save()
    {
        $keys  = array_keys(self::$config);
        $saved = [];
        foreach ($keys as $key) {
            $filename         = "$key.json";
            $saved[$filename] = file_put_contents(
                self::getConfigPath() . $filename,
                json_encode(self::$config),
                LOCK_EX
            );
        }
    }

    /**
     * Loads config data from a specific json file ONLY
     *
     * @param string|null $path
     *
     * @return string|false
     */
    public static function load(string $filename = null)
    {
        $path = self::getConfigPath() . $filename;

        $rawFile = file_get_contents($path);

        if ($rawFile) {
            $section                = pathinfo($path)['filename'];
            self::$config[$section] = json_decode($rawFile, true);
        }

        return $rawFile;
    }

    public static function loadAll()
    {
        $dir  = self::$configDir;
        $path = realpath(ROOT_DIR . DIRECTORY_SEPARATOR . $dir);

        if ( ! is_dir($path)) {
        }
        if ( ! file_exists($path)) {
        }

        $files = array_diff(scandir($path), array('..', '.', 'readme.md'));

        foreach ($files as $file) {
            self::load($file);
        }
    }

    public static function clear()
    {
        self::$config = [];
    }
}
