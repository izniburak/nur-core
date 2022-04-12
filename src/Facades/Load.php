<?php

namespace Nur\Facades;

use Nur\Kernel\Facade;

class Load extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'load';
    }
}