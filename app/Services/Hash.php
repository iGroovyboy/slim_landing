<?php

namespace App\Services;

class Hash
{

    /**
     * Hash the given value.
     *
     * @param string $value
     * @param array $options
     *
     * @return string
     *
     * @throws \Exception
     */
    public static function make($value, $options = null)
    {
        $hash = password_hash(
            $value,
            PASSWORD_BCRYPT,
            [
                'cost' => 12,
            ]
        );

        if ($hash === false) {
            $hash = password_hash($value,PASSWORD_DEFAULT);
        }

        return $hash;
    }

    /**
     * Check the given plain value against a hash.
     *
     * @param string $value
     * @param string $hashedValue
     * @param array $options
     *
     * @return bool
     *
     */
    public static function check($value, $hashedValue)
    {
        if (strlen($hashedValue) === 0) {
            return false;
        }

        return password_verify($value, $hashedValue);
    }

    public static function randomStr($length)
    {
        return bin2hex(random_bytes(($length - ($length % 2)) / 2));
    }

}
