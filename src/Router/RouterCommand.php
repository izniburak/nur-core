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

    /**
     * Resolve Controller or Middleware class.
     *
     * @param $class
     * @param $path
     * @param $namespace
     *
     * @return object
     * @throws
     */
    protected function resolveClass($class, $path, $namespace)
    {
        $class = $namespace . str_replace('/', '\\', $class);
        return resolve($class);
    }

    /**
     * @param $function
     * @param $params
     *
     * @return mixed
     */
    protected function runMethodWithParams($function, $params)
    {
        return app()->call($function, (!is_null($params) ? $params : []));
    }
}
