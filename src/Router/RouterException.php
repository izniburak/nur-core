<?php

namespace Nur\Router;

use Nur\Exception\ExceptionHandler;
use Nur\Load\Load;

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
        return Load::error("Opss! 404...", $message);
    }
}
