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
    protected function exception($message = ''): \Buki\Router\RouterException
    {
        return new RouterException($message);
    }

    /**
     * RouterCommand class
     *
     *
     * @return RouterCommand
     */
    protected function routerCommand(): \Buki\Router\RouterCommand
    {
        return RouterCommand::getInstance(
            $this->baseFolder, $this->paths, $this->namespaces,
            $this->request(), $this->response(),
            $this->getMiddlewares()
        );
    }

    /**
     * @param string $controller
     *
     * @return \Buki\Router\RouterException|mixed
     */
    protected function resolveClassName(string $controller)
    {
        if (strstr($controller, '\\')) {
            return ($controller);
        }

        return (str_replace(['.', '/'], ['\\'], $this->namespaces['controllers'] . $controller));
    }
}
