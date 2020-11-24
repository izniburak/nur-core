<?php

namespace Nur\Router;

use Buki\Router\Router as RouterProvider;

class Router extends RouterProvider
{
    /**
     * Throw new Exception for Router Error
     *
     * @param string $message
     * @param int    $statusCode
     *
     * @return RouterException
     */
    protected function exception($message = '', $statusCode = 500): \Buki\Router\RouterException
    {
        return new RouterException($message, $statusCode);
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

    /**
     * @return string
     */
    protected function getRequestUri(): string
    {
        $dirname = dirname($_SERVER['SCRIPT_NAME']);
        $dirname = $dirname === '/' ? '' : $dirname;
        $basename = 'index.php'; // basename($_SERVER['SCRIPT_NAME']);
        return $this->clearRouteName(str_replace([$dirname, $basename], null, $_SERVER['REQUEST_URI']));
    }
}
