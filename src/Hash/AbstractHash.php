<?php

namespace Nur\Hash;

/**
 * Class AbstractHash
 * Adapted from Laravel Framework
 * @see https://github.com/laravel/framework/tree/8.x/src/Illuminate/Hashing
 *
 * @package Nur\Hash
 */
abstract class AbstractHash
{
    /**
     * Get information about the given hashed value.
     *
     * @param string $hashedValue
     *
     * @return array
     */
    public function info($hashedValue)
    {
        return password_get_info($hashedValue);
    }

    /**
     * Check the given plain value against a hash.
     *
     * @param string $value
     * @param string $hashedValue
     *
     * @return bool
     */
    public function check($value, $hashedValue)
    {
        if (strlen($hashedValue) === 0) {
            return false;
        }

        return password_verify($value, $hashedValue);
    }
}
