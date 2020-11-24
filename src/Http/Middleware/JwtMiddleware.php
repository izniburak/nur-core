<?php

namespace Nur\Http\Middleware;

use Nur\Auth\Jwt\JwtException;
use Nur\Http\Middleware;

class JwtMiddleware extends Middleware
{
    /**
     * This method will be triggered
     * when the middleware is called
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            auth()->jwt()->check();
        } catch (JwtException $e) {
            return $this->failed();
        }

        return true;
    }

    /**
     * @return \Nur\Http\Response|string
     */
    public function failed()
    {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
        ], 401);
    }
}
