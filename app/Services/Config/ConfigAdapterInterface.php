<?php
declare(strict_types=1);

namespace App\Services\Config;

interface ConfigAdapterInterface
{

    /**
     * Loads config data from a specific file
     *
     * @return array|string|false
     */
    public static function load(string $filename);

    public static function save(string $filename, $data): bool;
}
