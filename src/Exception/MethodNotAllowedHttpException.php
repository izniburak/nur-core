<?php

namespace Nur\Exception;

/**
 * Class MethodNotAllowedHttpException
 * Adapted from Laravel Framework in order to use HTTP Exceptions
 *
 * @package Nur\Exception
 */
class MethodNotAllowedHttpException extends HttpException
{
    public function __construct(
        array $allow,
        $message = null,
        \Exception $previous = null,
        ?int $code = 0,
        array $headers = []
    ) {
        $headers['Allow'] = strtoupper(implode(', ', $allow));

        parent::__construct(405, $message, $previous, $headers, $code);
    }
}