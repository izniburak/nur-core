<?php

namespace Nur\Middleware;

interface MiddlewareInterface
{
    /**
     * This method will be triggered
     * when the middleware is called
     *
     * @return mixed
     */
    public function handle();
}
