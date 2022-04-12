<?php

namespace Nur\Router;

use Buki\Router\RouterCommand as RouterCommandProvider;
use ReflectionClass;
use Reflector;
use Symfony\Component\HttpFoundation\Response;

class RouterCommand extends RouterCommandProvider
{
    /**
     * Throw new Exception for Router Error
     *
     * @param string $message
     * @param int $statusCode
     *
     * @throws RouterException
     */
    public function exception(string $message = '', int $statusCode = 500)
    {
        throw new RouterException($message, $statusCode);
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
    protected function resolveClass(string $class, string $path, string $namespace): object
    {
        $class = str_replace([$namespace, '\\'], ['', '/'], $class);

        if ($this->namespaces['controllers'] === $namespace) {
            if (!str_starts_with($class, 'App')) {
                $class = $namespace . $class;
            }
        } elseif ($this->namespaces['middlewares'] === $namespace) {
            if (!str_contains($class, '/')) {
                $class = $namespace . $class;
            }

            if (str_contains($class, 'Nur/Http')) {
                $class =  str_replace($namespace, '', $class);
            }
        }

        $class = str_replace('/', '\\', $class);
        return resolve($class);
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
            $class = $param->getType() && !$param->getType()->isBuiltin()
                ? new ReflectionClass($param->getType()->getName())
                : null;
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
