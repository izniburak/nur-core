<?php

namespace Nur\Exception;

/**
 * Class UnauthorizedHttpException
 * Adapted from Laravel Framework in order to use HTTP Exceptions
 *
 * @package Nur\Exception
 */
class UnauthorizedHttpException extends HttpException
{
    /**
     * @param string        $challenge  WWW-Authenticate challenge string
     * @param string|array  $message    The internal exception message
     * @param \Exception    $previous   The previous exception
     * @param int           $code       The internal exception code
     * @param array         $headers
     */
    public function __construct(
        string $challenge,
        $message = null,
        \Exception $previous = null,
        ?int $code = 0,
        array $headers = []
    ) {
        $headers['WWW-Authenticate'] = $challenge;

        parent::__construct(401, $message, $previous, $headers, $code);
    }
}
