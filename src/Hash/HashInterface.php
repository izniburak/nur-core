<?php

namespace Nur\Hash;

/**
 * Interface HashInterface
 * Adapted from Laravel Framework
 * @see https://github.com/laravel/framework/tree/8.x/src/Illuminate/Hashing
 *
 * @package Nur\Hash
 */
interface HashInterface
{
    /**
     * Get information about the given hashed value.
     *
     * @param  string $hashedValue
     *
     * @return array
     */
    public function info(string $hashedValue): array;

    /**
     * Hash the given value.
     *
     * @param  string $value
     * @param  array  $options
     *
     * @return string
     */
    public function make(string $value, array $options = []): string;

    /**
     * Check the given plain value against a hash.
     *
     * @param  string $value
     * @param  string $hashedValue
     *
     * @return bool
     */
    public function check(string $value, string $hashedValue): bool;

    /**
     * Check if the given hash has been hashed using the given options.
     *
     * @param  string $hashedValue
     * @param  array  $options
     *
     * @return bool
     */
    public function needsRehash(string $hashedValue, array $options = []): bool;
}