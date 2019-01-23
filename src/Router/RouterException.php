<?php

namespace Nur\Router;

use Nur\Exception\NotFoundHttpException;

class RouterException
{
    /**
     * Create Exception Class.
     *
     * @param string $message
     *
     * @return mixed
     */
    public function __construct($message)
    {
        throw new NotFoundHttpException;
    }
}
