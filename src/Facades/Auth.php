<?php

namespace Nur\Facades;

use Nur\Kernel\Facade;

/**
 * @method static bool check()
 * @method static bool guest()
 * @method static \Nur\Database\Model|null user()
 * @method static int|null id()
 * @method static bool validate(array $credentials = [])
 * @method static bool attempt(array $credentials = [], bool $remember = false)
 * @method static bool login(\Nur\Database\Model $user, bool $remember = false)
 * @method static bool loginUsingId(mixed $id, bool $remember = false)
 * @method static void logout()
 *
 * @see \Nur\Auth\Auth
 */
class Auth extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Nur\Auth\Auth::class;
    }
}
