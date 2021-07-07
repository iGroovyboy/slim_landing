<?php

declare(strict_types=1);

namespace App\Services\Config;

use App\Services\Str;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

final class Env extends Config
{
    protected static PropertyAccessorInterface $propertyAccessor;

    protected static string $configDir;

    protected static ConfigAdapterInterface $adapter;

    protected static array $config;

    const ENV_PREFIX = 'env_';

    public static function getAll()
    {
        return static::$config;
    }

    public static function load(string $filename = null): bool
    {
        if ( ! Str::str_starts_with($filename, static::ENV_PREFIX)) {
            return false;
        }

        $data = static::$adapter::load(static::$configDir . DS . $filename);

        if ($data) {
            $section                  = pathinfo(static::$configDir . DS . $filename)['filename'];
            $section                  = ltrim($section, static::ENV_PREFIX);
            static::$config[$section] = $data;

            return true;
        }

        return false;
    }

    public static function save(string $section): bool
    {
        $filename = static::ENV_PREFIX . $section . static::$adapter::EXT;
        $path = static::$configDir . DS . $filename;

        return static::$adapter::save($path, static::$config[$section]);
    }

    public static function saveAll(): void
    {
        foreach (static::$config as $key => $data) {
            static::save($key);
        }
    }


}
