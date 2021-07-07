<?php


namespace App\Services\Config;

use Symfony\Component\PropertyAccess\PropertyAccess as PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class Config implements ConfigInterface
{

    private static PropertyAccessorInterface $propertyAccessor;

    private static string $configDir;

    private static ConfigAdapterInterface $adapter;

    private static array $config;

    const EXCLUDE = ['..', '.'];

    const PATHS   = 'app/paths';

    public static function init(string $pathToConfig, ConfigAdapterInterface $adapter)
    {
        static::$propertyAccessor = PropertyAccessor::createPropertyAccessorBuilder()
                                      ->enableExceptionOnInvalidIndex()
                                      ->getPropertyAccessor();
        static::setConfigDir($pathToConfig);

        static::setConfigAdapter($adapter, $pathToConfig);
    }

    public static function setConfigDir($pathToConfig)
    {
        static::$configDir = $pathToConfig;
    }

    public static function setConfigAdapter(ConfigAdapterInterface $adapter, ?string $pathToConfig)
    {
        static::$adapter = $adapter;
    }

    public static function get(string $propertyPath, string $default = '')
    {
        $path = static::convertPathToArrayNotation($propertyPath);

        return static::$propertyAccessor->getValue(static::$config, $path) ?: $default;
    }

    public static function getPath($path, $suffix = ''): string
    {
        $path = str_replace(['/', '\\'], DS, static::get($path));

        $path = static::replaceKeysInPath( $path, static::get(static::PATHS) );

        return ROOT_DIR . DS . $path . DS . $suffix;
    }

    /**
     * Replaces keys in curly braces in strings like '{key0}/two/{key1}' with values from array to get string like 'one/two/three'
     */
    private static function replaceKeysInPath(string $path, array $vars): string
    {
        $keys = static::getKeysFromPath($path);

        foreach ($keys as $key) {
            $k = str_replace(['{','}'], '', $key);
            if(!isset($vars[$k])){
                continue;
            }

            $path = str_replace($key, $vars[$k], $path);
        }

        return $path;
    }

    /**
     * Extracts parts wrapped with curly braces from strings like '{one}/dfsdf/{two}\sdfs/{three}'
     */
    public static function getKeysFromPath(string $string): ?array
    {
        preg_match_all( '/{\S[^\/\\\\]*}/', $string, $matches, PREG_PATTERN_ORDER, 0);
        return $matches[0];
    }

    public static function set(string $propertyPath, string $value)
    {
        $path = static::convertPathToArrayNotation($propertyPath);

        static::$propertyAccessor->setValue(static::$config, $path, $value);
    }

    public static function has(string $propertyPath): bool
    {
        $path = static::convertPathToArrayNotation($propertyPath);

        return static::$propertyAccessor->getValue(static::$config, $path);
    }

    public static function save(string $section): bool
    {
    }

    public static function saveAll(): void
    {
    }

    public static function load(string $filename = null): bool
    {
        $data = static::$adapter::load(static::$configDir . $filename);

        if ($data) {
            $section                  = pathinfo(static::$configDir . $filename)['filename'];
            static::$config[$section] = $data;

            return true;
        }

        return false;
    }

    public static function loadAll(?array $except = [])
    {
        $except = array_unique(array_merge(self::EXCLUDE, $except));

        $dir  = static::$configDir;

        $files = array_diff(scandir($dir), $except);

        foreach ($files as $file) {
            static::load($file);
        }
    }

    /**
     * Converts path like 'app/section/key' to '[app][section][key]'
     */
    protected static function convertPathToArrayNotation($propertyPath): string
    {
        return '[' . str_replace('/', '][', $propertyPath) . ']';
    }


}
