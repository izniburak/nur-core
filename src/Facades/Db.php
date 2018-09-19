<?php

namespace Nur\Facades;

use Nur\Kernel\Facade;

class Db extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'builder';
    }
}