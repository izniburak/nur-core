<?php

namespace Nur\Router;

class Route
{
    /**
     * Class instance variable
     *
     * @var Nur\Router\Router
     */
    private static $instance = null;

    /**
     * Get class instance
     *
     * @return Nur\Router\Router
     * @throws
     */
    public function __construct()
    {
        if (null === self::$instance) {
            self::$instance = new Router([
                'base_folder' => base_path(),
                'main_method' => 'main',
                'paths' => [
                    'controllers' => 'app/Controllers/',
                    'middlewares' => 'app/Middlewares/',
                ],
                'namespaces' => [
                    'controllers' => 'App\Controllers',
                    'middlewares' => 'App\Middlewares',
                ],
                'cache' => cache_path('routes.php'),
            ]);
        }

        return self::$instance;
    }

    /**
     * Call function for Class
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return self::__callStatic($method, $parameters);
    }

    /**
     * Call static function for Class
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return call_user_func_array([self::$instance, $method], $parameters);
    }
}