<?php

namespace Nur\Facades;

use Nur\Kernel\Facade;

/**
 * @method static string encode($payload, $key, $alg = 'HS256', $keyId = null, $head = null)
 * @method static object decode($jwt, $key, array $allowed_algs = [])
 * @method static string sign($msg, $key, $alg = 'HS256')
 * @method static int getLeeway()
 * @method static void setLeeway(int $leeway)
 *
 * @see \Nur\Auth\Jwt\Jwt
 */
class Jwt extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Nur\Auth\Jwt\Jwt::class;
    }
}
