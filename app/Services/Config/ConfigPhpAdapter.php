<?php

declare(strict_types=1);

namespace App\Services\Config;

use function Composer\Autoload\includeFile;

class ConfigPhpAdapter implements ConfigAdapterInterface
{
    private static $instance;

    const EXT = 'php';

    public static function load(string $filename)
    {
        if ( ! static::isPHPfile($filename)) {
            return false;
        }

        $data = require_once($filename);

        return $data;
    }

    public static function save(string $filename, $data): bool
    {
        return false;
    }

    public static function getInstance(): self
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    private static function isPHPfile(string $filename)
    {
        if ( ! file_exists($filename)) {
            throw new FileNotExistsException($filename);
        }

        $path_parts = pathinfo($filename);

        return static::EXT === mb_strtolower($path_parts['extension']);
    }
}
