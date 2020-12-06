<?php

namespace Nur\Router;

use Buki\Router\RouterCommand as RouterCommandProvider;
use Reflector;
use Symfony\Component\HttpFoundation\Response;

class RouterCommand extends RouterCommandProvider
{
    /**
     * Throw new Exception for Router Error
     *
     * @param string $message
     * @param int    $statusCode
     *
     * @return RouterException
     */
    public function exception($message = '', $statusCode = 500)
    {
        return new RouterException($message, $statusCode);
    }

    /**
     * Resolve Controller or Middleware class.
     *
     * @param string $class
     * @param string $path
     * @param string $namespace
     *
     * @return object
     * @throws
     */
    protected function resolveClass(string $class, string $path, string $namespace)
    {
        $class = str_replace([$namespace, '\\'], ['', '/'], $class);
        $class = $namespace . str_replace('/', '\\', $class);
        return resolve($class);
    }

    /**
     * @param $response
     *
     * @return Response|mixed
     */
    protected function sendResponse($response)
    {
        if (is_array($response)) {
            return $this->response->json($response)->send();
        }

        if (!is_string($response)) {
            return $response instanceof Response ? $response->send() : print($response);
        }

        return $this->response->setContent($response)->send();
    }

    /**
     * @param Reflector $reflection
     * @param array     $uriParams
     *
     * @return array
     */
    protected function resolveCallbackParameters(Reflector $reflection, array $uriParams): array
    {
        $parameters = [];
        foreach ($reflection->getParameters() as $key => $param) {
            $class = $param->getClass();
            if (!is_null($class) && $class->isInstance($this->request)) {
                $parameters[] = $this->request;
            } elseif (!is_null($class) && $class->isInstance($this->response)) {
                $parameters[] = $this->response;
            } elseif (!is_null($class)) {
                $parameters[] = resolve($class->getName());
            } else {
                if (empty($uriParams)) {
                    continue;
                }
                $uriParams = array_reverse($uriParams);
                $parameters[] = array_pop($uriParams);
                $uriParams = array_reverse($uriParams);
            }
        }

        return $parameters;
    }
}
