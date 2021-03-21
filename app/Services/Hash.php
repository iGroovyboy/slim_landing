<?php

namespace App\Services;

class Hash
{

    /**
     * Hash the given value.
     *
     * @param  string  $value
     * @param  array  $options
     * @return string
     *
     * @throws \Exception
     */
    public static function make($value)
    {
        $hash = password_hash($value, PASSWORD_BCRYPT, [
            'cost' => 12,
        ]);

        if ($hash === false) {
            throw new \Exception('Bcrypt hashing not supported.');
        }

        return $hash;
    }

    /**
     * Check the given plain value against a hash.
     *
     * @param  string  $value
     * @param  string  $hashedValue
     * @param  array  $options
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

}
