<?php

namespace Nur\Router;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Route
{
    /**
     * Class instance variable
     *
     * @var Router
     */
    private static $instance = null;

    /**
     * Get class instance
     *
     * @return Router
     * @throws
     */
    public function __construct()
    {
        if (null === self::$instance) {
            $config = config('route');
            self::$instance = new Router(
                [
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
                    'debug' => !app()->isProduction(),
                ],
                request(),
                response(),
            );

            // Set Middlewares
            self::$instance->setMiddleware($config['middleware']);
            self::$instance->setMiddlewareGroup($config['middlewareGroup']);
            self::$instance->setRouteMiddleware($config['routeMiddleware']);

            self::$instance->notFound(function (Request $request, Response $response) {
                $response->setStatusCode(Response::HTTP_NOT_FOUND);
                $response->setContent('Looks like page not found or something went wrong. Please try again.');
                return $response;
            });

            self::$instance->error(function (Request $request, Response $response, \Exception $e) {
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
                $response->setContent($e->getMessage());
                return $response;
            });
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