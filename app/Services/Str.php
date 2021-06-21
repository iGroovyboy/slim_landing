<?php


namespace App\Services;


class Str
{
    public static function maybeUnserialize(string $val)
    {
        return self::isSerialized($val) ? unserialize($val) : $val;
    }

    /**
     * @link https://developer.wordpress.org/reference/functions/is_serialized/
     */
    public static function isSerialized(string $data, bool $strict = true): bool
    {
        // If it isn't a string, it isn't serialized.
        if ( ! is_string($data)) {
            return false;
        }
        $data = trim($data);
        if ('N;' === $data) {
            return true;
        }
        if (strlen($data) < 4) {
            return false;
        }
        if (':' !== $data[1]) {
            return false;
        }
        if ($strict) {
            $lastc = substr($data, -1);
            if (';' !== $lastc && '}' !== $lastc) {
                return false;
            }
        } else {
            $semicolon = strpos($data, ';');
            $brace     = strpos($data, '}');
            // Either ; or } must exist.
            if (false === $semicolon && false === $brace) {
                return false;
            }
            // But neither must be in the first X characters.
            if (false !== $semicolon && $semicolon < 3) {
                return false;
            }
            if (false !== $brace && $brace < 4) {
                return false;
            }
        }
        $token = $data[0];
        switch ($token) {
            case 's':
                if ($strict) {
                    if ('"' !== substr($data, -2, 1)) {
                        return false;
                    }
                } elseif (false === strpos($data, '"')) {
                    return false;
                }
            // Or else fall through.
            case 'a':
            case 'O':
                return (bool)preg_match("/^{$token}:[0-9]+:/s", $data);
            case 'b':
            case 'i':
            case 'd':
                $end = $strict ? '$' : '';

                return (bool)preg_match("/^{$token}:[0-9.E+-]+;$end/", $data);
        }

        return false;
    }

    // https://github.com/laravel/framework/blob/ceb3217fa65e9cfb7ca8287f5e2aca11467fd8a1/src/Illuminate/Support/Str.php#L713
    public static function str_starts_with($haystack, $needle) {
        foreach ((array) $needle as $needle) {
            if ((string) $needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0) {
                return true;
            }
        }

        return false;
    }

    public static function isJson($string) {


        if ( ! self::str_starts_with($string, '{') && ! self::str_starts_with($string, '[')) {
            return false;
        }
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
