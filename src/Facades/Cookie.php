<?php

namespace Nur\Facades;

use Nur\Kernel\Facade;

class Cookie extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Nur\Http\Cookie::class;
    }
}