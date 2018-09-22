<?php

namespace Nur\Exception;

class MethodNotAllowedHttpException extends HttpException
{
    /**
     * @param array      $allow    An array of allowed methods
     * @param string     $message  The internal exception message
     * @param \Exception $previous The previous exception
     * @param int        $code     The internal exception code
     * @param array      $headers
     */
    public function __construct(
        array $allow,
        string $message = null,
        \Exception $previous = null,
        ?int $code = 0,
        array $headers = []
    ) {
        $headers['Allow'] = strtoupper(implode(', ', $allow));

        parent::__construct(405, $message, $previous, $headers, $code);
    }
}