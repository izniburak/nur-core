<?php

namespace Nur\Facades;

use Nur\Kernel\Facade;

class Sql extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'sql';
    }
}