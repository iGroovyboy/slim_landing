<?php
declare(strict_types=1);

namespace App\Services\Config;

use App\Services\Config\ConfigAdapterInterface;

class ConfigJsonAdapter implements ConfigAdapterInterface
{

    const EXT = 'json';

    private static $instance;

    public static function load(string $filename)
    {
        if ( ! static::isJSONfile($filename)) {
            return false;
        }

        $data = json_decode(file_get_contents($filename), true);

        return $data;
    }

    public static function save(string $filename, $data): bool
    {
        return file_put_contents($filename, json_encode($data));
    }

    public static function getInstance(): self
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    private static function isJSONfile(string $filename)
    {
        if ( ! file_exists($filename)) {
            throw new FileNotExistsException($filename);
        }

        $path_parts = pathinfo($filename);

        return static::EXT === mb_strtolower($path_parts['extension']);
    }

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

}
