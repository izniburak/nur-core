<?php

namespace Nur\Middleware;

use Nur\Http\Request;

abstract class Middleware implements MiddlewareInterface
{
    /**
     * @var Request $request
     */
    private static $request;

    /**
     * Create Base Middleware.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct()
    {
        self::$request = app()->make(Request::class);
    }

    /**
     * @return Request
     */
    protected function request()
    {
        return self::$request;
    }

    /**
     * This method will be triggered
     * when the middleware is called
     *
     * @return bool
     */
    abstract public function handle();
}
