<?php

namespace Nur\Router;

use Nur\Exception\NotFoundHttpException;

class RouterException extends \Buki\Router\RouterException
{
    /**
     * Create Exception Class.
     *
     * @param string $message
     * @param int    $statusCode
     *
     * @return mixed
     */
    public function __construct(string $message, $statusCode = 500)
    {
        throw new NotFoundHttpException($message);
    }
}
