<?php


namespace App\Services;


class Path
{

    /**
     * Extracts parts wrapped with curly braces from strings like '{one}/dfsdf/{two}\sdfs/{three}'
     */
    public static function getKeysFromPath(string $string): ?array
    {
        preg_match_all( '/{\S[^\/\\\\]*}/', $string, $matches, PREG_PATTERN_ORDER, 0);
        return $matches[0];
    }

    /**
     * Replaces keys in curly braces in strings like '{key0}/two/{key1}' with values from array to get string like 'one/two/three'
     */
    public static function replaceKeysInPath(string $path, array $vars): string
    {
        $keys = Path::getKeysFromPath($path);

        foreach ($keys as $key) {
            $k = str_replace(['{','}'], '', $key);
            if(!isset($vars[$k])){
                continue;
            }

            $path = str_replace($key, $vars[$k], $path);
        }

        return $path;
    }
}
