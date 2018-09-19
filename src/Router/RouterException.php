<?php

namespace Nur\Router;

use Nur\Exception\ExceptionHandler;

class RouterException extends ExceptionHandler
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
        return error('Opss! 404...', $message);
    }
}
