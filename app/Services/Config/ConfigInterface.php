<?php
declare(strict_types=1);

namespace App\Services\Config;

interface ConfigInterface
{

    public static function get(string $propertyPath, string $default = '');

    public static function set(string $propertyPath, string $value);

    public static function has(string $propertyPath): bool;

    public static function load(): bool;

    public static function save(string $section): bool;
}
