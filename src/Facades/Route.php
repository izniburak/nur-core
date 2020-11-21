<?php

namespace Nur\Facades;

use Closure;
use Nur\Kernel\Facade;

/**
 * @method static any(string $route, string|Closure $callback, array $options = [])
 * @method static get(string $route, string|Closure $callback, array $options = [])
 * @method static post(string $route, string|Closure $callback, array $options = [])
 * @method static put(string $route, string|Closure $callback, array $options = [])
 * @method static delete(string $route, string|Closure $callback, array $options = [])
 * @method static patch(string $route, string|Closure $callback, array $options = [])
 * @method static head(string $route, string|Closure $callback, array $options = [])
 * @method static options(string $route, string|Closure $callback, array $options = [])
 * @method static ajax(string $route, string|Closure $callback, array $options = [])
 * @method static xpost(string $route, string|Closure $callback, array $options = [])
 * @method static xput(string $route, string|Closure $callback, array $options = [])
 * @method static xdelete(string $route, string|Closure $callback, array $options = [])
 * @method static xpatch(string $route, string|Closure $callback, array $options = [])
 * @method static add(string $methods, string $route, string|Closure $callback, array $options = [])
 * @method static controller(string $route, string $controller, array $options = [])
 * @method static group(string $route, Closure $callback, array $options = [])
 * @method static pattern(array|string $name, $pattern = null)
 * @method static getRoutes()
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