<?php

namespace Nur\Facades;

use Nur\Kernel\Facade;

class Session extends Facade
{
    /**
     * Get the registered name of the component.
     * 
     * @param string
     */
    protected static function getFacadeAccessor()
    {
        return 'session';
    }
}