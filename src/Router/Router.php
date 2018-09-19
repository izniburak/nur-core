<?php

namespace Nur\Router;

use Buki\Router as RouterProvider;

class Router extends RouterProvider
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

    /**
     * RouterCommand class
     *
     * @param string $message
     *
     * @return RouterCommand
     */
    public function routerCommand($message = '')
    {
        return new RouterCommand($message);
    }
}
