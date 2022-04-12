<?php

namespace Nur\Facades;

use Nur\Kernel\Facade;

class Log extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'log';
    }
}