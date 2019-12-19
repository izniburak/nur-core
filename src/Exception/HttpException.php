<?php

namespace Nur\Exception;

class HttpException extends \RuntimeException
{
    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var array
     */
    protected $errorMessages = [
        400 => ['title' => 'Bad Request', 'message' => 'Sorry, your request is invalid.'],
        401 => ['title' => 'Unauthorized', 'message' => 'Sorry, you are not authorized to access this page.'],
        403 => ['title' => 'Forbidden', 'message' => 'Sorry, you are forbidden from accessing this page.'],
        404 => ['title' => 'Page Not Found', 'message' => 'Sorry, the page you are looking for could not be found.'],
        405 => ['title' => 'Method Not Allowed', 'message' => 'Sorry, your request method is not allowed.'],
        419 => ['title' => 'Page Expired', 'message' => 'Sorry, your session has expired. Please refresh and try again.'],
        429 => ['title' => 'Too Many Requests', 'message' => 'Sorry, you are making too many requests to our servers.'],
        500 => ['title' => 'Service Error', 'message' => 'Whoops, something went wrong on our servers.'],
        503 => ['title' => 'Service Unavailable', 'message' => 'Sorry, we are doing some maintenance. Please check back soon.'],
    ];

    /**
     * HttpException constructor.
     *
     * @param int             $statusCode
     * @param null            $messageText
     * @param \Exception|null $previous
     * @param array           $headers
     * @param int|null        $code
     */
    public function __construct(
        int $statusCode,
        $messageText = null,
        \Exception $previous = null,
        array $headers = [],
        ?int $code = 0
    ) {
        $this->statusCode = $statusCode;
        $this->headers = $headers;

        http_response_code($statusCode);

        if (config('app.env') !== 'prod') {
            $message = is_array($messageText) ? implode(' - ', $messageText) : $messageText;
            return parent::__construct($message, $code, $previous);
        }

        $title = null;
        if (is_array($messageText)) {
            $title = isset($messageText['title']) ? $messageText['title'] : null;
            $messageText = isset($messageText['message']) ? $messageText['message'] : null;
        }

        $message = $messageText ?? null;
        if (in_array($statusCode, array_keys($this->errorMessages))) {
            $title = $title === null 
                    ? $this->errorMessages[$statusCode]['title']
                    : $title;
            $message = $message === null 
                    ? $this->errorMessages[$statusCode]['message']
                    : $message;
        }

        $title = $title ?? 'System Error';
        $message = $message ?? 'Whoops, something went wrong on the system.';

        if (request()->headers->get('content-type') === 'application/json') {
            echo response()->json([
                'success' => false,
                'error' => $message,
            ], $statusCode);
            return false;
        }

        return require __DIR__ . '/views/index.php';
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Set response headers.
     *
     * @param array $headers Response headers
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }
}