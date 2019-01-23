<?php

namespace Nur\Exception;

class NotFoundHttpException extends HttpException
{
    /**
     * @param string|array  $message  The internal exception message
     * @param \Exception    $previous The previous exception
     * @param int           $code     The internal exception code
     * @param array         $headers
     */
    public function __construct($message = null, \Exception $previous = null, int $code = 0, array $headers = [])
    {
        parent::__construct(404, $message, $previous, $headers, $code);
    }
}