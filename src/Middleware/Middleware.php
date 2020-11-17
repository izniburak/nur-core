<?php

namespace Nur\Middleware;

class Middleware implements MiddlewareInterface
{
    /**
     * Create Base Middleware.
     *
     * @return void
     */
    function __construct() { }

    /**
     * This method will be triggered
     * when the middleware is called
     *
     * @return mixed
     */
    public function handle()
    {
        return true;
    }
}
