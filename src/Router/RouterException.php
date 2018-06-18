<?php

namespace Nur\Router;

use Nur\Exception\ExceptionHandler;

class RouterException extends ExceptionHandler
{
    /**
     * Create Exception Class.
     *
     * @param string $message
     * @return void
     */
    public function __construct($message)
    {
        parent::__construct("Opps! 404 Not Found.", $message);
    }
}
