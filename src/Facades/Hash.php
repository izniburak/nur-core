<?php

namespace Nur\Facades;

use Nur\Kernel\Facade;

/**
 * @method static array info(string $hashedValue)
 * @method static string make(string $value, array $options = [])
 * @method static bool check(string $value, string $hashedValue, array $options = [])
 * @method static bool needsRehash(string $hashedValue, array $options = [])
 *
 * @see \Nur\Hash\Hash
 */
class Hash extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'hash';
    }
}