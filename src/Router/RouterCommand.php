<?php

namespace Nur\Router;

use Buki\Router\RouterCommand as RouterCommandProvider;

class RouterCommand extends RouterCommandProvider
{
    /**
     * Throw new Exception for Router Error
     *
     * @param string $message
     *
     * @return RouterException
     */
    public function exception($message = '')
    {
        return new RouterException($message);
    }
}
