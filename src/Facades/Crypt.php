<?php

namespace Nur\Facades;

use Nur\Kernel\Facade;

/**
 * @method static string encrypt(string $value, bool $serialize = true)
 * @method static string decrypt(string $payload, bool $unserialize = true)
 *
 * @see \Nur\Encryption\Encrypter
 */
class Crypt extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'encrypter';
    }
}