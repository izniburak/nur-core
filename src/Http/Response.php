<?php

namespace Nur\Http;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class Response extends SymfonyResponse
{
    /** @var bool $json */
    protected $json = false;

    /**
     * Class constructor
     *
     * @param mixed $content
     * @param int   $status
     * @param array $headers
     *
     * @return void
     */
    public function __construct($content = '', int $status = 200, array $headers = [])
    {
        // Remove all headers
        header_remove();

        parent::__construct($content, $status, $headers);
        $this->setCharset('utf-8');
        $this->headers->set('Content-Type', 'text/html; charset=' . $this->getCharset());
    }

    /**
     * Json response
     *
     * @param array|object $data
     * @param int          $statusCode
     *
     * @return Response
     */
    public function json($data = null, int $statusCode = 200)
    {
        if (is_null($data)) {
            $data = [];
        }
        $this->setContent(json_encode($data));
        $this->setStatusCode($statusCode);
        $this->json = true;

        return $this;
    }

    /**
     * Add custom header data in Response
     *
     * @param array|string $key
     * @param string       $value
     *
     * @return Response
     */
    public function header($key, $value)
    {
        if (is_array($key) && ! empty($key)) {
            foreach ($key as $k => $v) {
                $this->headers->set($k, $v);
            }
        } elseif (is_string($key) && ! empty($key)) {
            $this->headers->set($key, $value);
        }
        return $this;
    }

    /**
     * Return view file within Response
     *
     * @param string $view
     * @param array  $data
     *
     * @return Response|void
     */
    public function view($view, array $data = [])
    {
        if (function_exists('app')) {
            $this->setContent(
                app('load')->view($view, $data)
            );

            return $this;
        }

        return;
    }

    /**
     * Return blade file within Response
     *
     * @param string $view
     * @param array  $data
     * @param array  $mergeData
     *
     * @return Response|void
     */
    public function blade($view, array $data = [], array $mergeData = [])
    {
        if (function_exists('app')) {
            $this->setContent(
                app('view')->make($view, $data, $mergeData)->render()
            );

            return $this;
        }

        return;
    }

    /**
     * @return Response|string
     */
    public function __toString()
    {
        if ($this->json) {
            $this->headers->set('Content-Type', 'application/json; charset=' . $this->getCharset());
        }

        return $this->send();
    }
}
