<?php

namespace Nur\Http\Middleware;

use Nur\Http\Middleware;
use Nur\Http\Response;

class CorsMiddleware extends Middleware
{
    /**
     * [TODO] - This middleware will be updated.
     *
     * @param Response $response
     *
     * @return bool
     */
    public function handle(Response $response): bool
    {
        $cors = config('cors');
        $response->headers->set('Access-Control-Allow-Origin', $cors['origin']);
        $response->headers->set('Access-Control-Allow-Methods', $cors['methods']);
        $response->headers->set('Access-Control-Allow-Headers', $cors['headers.allow']);
        if (!empty($cors['headers.expose'])) {
            $response->headers->set('Access-Control-Expose-Headers', $cors['headers.expose']);
        }
        $response->headers->set('Access-Control-Allow-Credentials', $cors['credentials']);
        $response->headers->set('Access-Control-Max-Age', $cors['cache']);

        return true;
    }
}