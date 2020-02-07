<?php

namespace Nur\Http;

/**
 * Class Response
 * Adapted from symfony/http-foundation package
 *
 * @package Nur\Http
 */
class Response extends \Symfony\Component\HttpFoundation\Response
{
    /** @var bool */
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
    public function json($data = null, int $statusCode = 200): Response
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
     * @param string|null  $value
     *
     * @return Response
     */
    public function header($key, string $value = null): Response
    {
        if (is_array($key) && !empty($key)) {
            foreach ($key as $k => $v) {
                $this->headers->set($k, $v);
            }
        } elseif (is_string($key) && !empty($key)) {
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
     * @return Response|null
     */
    public function view(string $view, array $data = []): ?Response
    {
        $this->setContent(
            app('load')->view($view, $data)
        );

        return $this;
    }

    /**
     * Return blade file within Response
     *
     * @param string $view
     * @param array  $data
     * @param array  $mergeData
     *
     * @return Response|null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function blade(string $view, array $data = [], array $mergeData = []): ?Response
    {
        $this->setContent(
            app('view')->make($view, $data, $mergeData)->render()
        );

        return $this;
    }

    /**
     * @return Response|string
     */
    public function __toString()
    {
        if ($this->json) {
            $this->headers->set('Content-Type', 'application/json; charset=' . $this->getCharset());
        }

        // return $this->send();
        return $this->sendHeaders()->getContent();
    }
}
