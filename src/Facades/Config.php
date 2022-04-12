<?php

namespace Nur\Facades;

use Nur\Kernel\Facade;

/**
 * @method static bool has($key)
 * @method static mixed get($key, $default = null)
 * @method static array all()
 * @method static void set($key, $value = null)
 * @method static void prepend($key, $value)
 * @method static void push($key, $value)
 *
 * @see \Nur\Config\Config
 */
class Config extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'config';
    }
}
