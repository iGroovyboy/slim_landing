<?php


namespace App\Services;


class Arr
{
    public static function noSensitiveData(array $array): array
    {
        return array_filter(
            $array,
            function ($k) {
                return strpos($k, 'pass') === false;
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    public static function toString(array $array, bool $includeKeys = true): string
    {
        if ( ! $includeKeys) {
            return implode(', ', $array);
        }

        $array = array_map(
            function ($k, $v) use ($includeKeys) {
                return "$k = $v";
            },
            array_keys($array),
            array_values($array)
        );

        return implode(', ', $array);
    }

    public static function only($arr, $key)
    {
        return array_filter(
            $arr,
            function ($id) use ($key) {
                return is_array($key) ? in_array($id, $key) : $id === $key;
            },
            ARRAY_FILTER_USE_KEY
        );
    }
}
