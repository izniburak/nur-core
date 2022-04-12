<?php

namespace Nur\Exception;

/**
 * Class NotFoundHttpException
 * Adapted from Laravel Framework in order to use HTTP Exceptions
 *
 * @package Nur\Exception
 */
class NotFoundHttpException extends HttpException
{
    public function __construct($message = null, \Exception $previous = null, int $code = 0, array $headers = [])
    {
        parent::__construct(404, $message, $previous, $headers, $code);
    }
}