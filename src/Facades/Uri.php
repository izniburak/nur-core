<?php

namespace Nur\Facades;

use Nur\Kernel\Facade;

class Uri extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'uri';
    }
}