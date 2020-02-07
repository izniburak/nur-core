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
    /**
     * @param array             $allow    An array of allowed methods
     * @param string|array      $message  The internal exception message
     * @param \Exception        $previous The previous exception
     * @param int               $code     The internal exception code
     * @param array             $headers
     */
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