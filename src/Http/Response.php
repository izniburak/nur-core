<?php

namespace Nur\Http;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class Response extends SymfonyResponse
{
    /** @var bool $json */
    protected $json = false;

    /**
     * Class constructer
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
     * @param array $data
     * @param int   $statusCode
     *
     * @return Response
     */
    public function json(array $data = [], int $statusCode = 200)
    {
        $this->setContent(json_encode($data));
        $this->setStatusCode($statusCode);
        $this->json = true;

        return $this;
    }

    /**
     * Add custom header data in Response
     *
     * @param array $data
     *
     * @return Response
     */
    public function addHeaders(array $data = [])
    {
        foreach ($data as $key => $value) {
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
                app('blade')->make($view, $data, $mergeData)
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
