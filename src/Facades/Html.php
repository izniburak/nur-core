<?php

namespace Nur\Facades;

use Nur\Kernel\Facade;

class Html extends Facade
{
    /**
     * Get the registered name of the component.
     * 
     * @param string
     */
    protected static function getFacadeAccessor()
    {
        return 'html';
    }
}