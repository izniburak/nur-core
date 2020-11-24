<?php

namespace Nur\Http\Middleware;

use Nur\Exception\BadRequestHttpException;
use Nur\Http\Middleware;
use Nur\Http\Request;

class CsrfMiddleware extends Middleware
{
    /**
     * This method will be triggered
     * when the middleware is called
     *
     * @param Request $request
     *
     * @return bool
     */
    public function handle(Request $request): bool
    {
        if (!in_array($request->method(), ['HEAD', 'GET', 'OPTIONS'])) {
            $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');
            if (!csrf_check($token)) {
                throw new BadRequestHttpException;
            }
        }

        return true;
    }
}
