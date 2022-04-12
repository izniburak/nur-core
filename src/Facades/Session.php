<?php

namespace Nur\Facades;

use Nur\Kernel\Facade;

class Session extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Nur\Http\Session::class;
    }
}