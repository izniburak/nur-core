<?php

namespace Nur\Router;

use Nur\Router\RouterException;
use Buki\Router\RouterCommand as RouterCommandProvider;

class RouterCommand extends RouterCommandProvider
{
    /**
     * Throw new Exception for Router Error
     *
     * @param string $message
     * @return Nur\Router\RouterException
     */
    public function exception($message = '')
    {
        return new RouterException($message);
    }
}
