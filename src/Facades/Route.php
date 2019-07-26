<?php

namespace Nur\Facades;

use Nur\Kernel\Facade;

/**
 * @method static mixed controller(string $path, $controller, $config = null)
 * @method static mixed get(string $path, $callback, $config = null)
 * @method static mixed post(string $path, $callback, $config = null)
 * @method static mixed put(string $path, $callback, $config = null)
 * @method static mixed delete(string $path, $callback, $config = null)
 * @method static mixed ajax(string $path, $callback, $config = null)
 * @method static mixed ajaxp(string $path, $callback, $config = null)
 * @method static mixed any(string $path, $callback, $config = null)
 * @method static mixed add(string $httpMethods, $path, $callback, $config = null)
 * @method static mixed group(string $path, $callback, $config = null)
 * @method static mixed pattern(array|string $name, $pattern = null)
 * @method static mixed getRoutes()
 *
 * @see \Nur\Router\Route
 */
class Route extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'route';
    }
}