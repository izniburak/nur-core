<?php

namespace Nur\Http\Middleware;

use Nur\Http\Middleware;

class AuthMiddleware extends Middleware
{
    /**
     * This method will be triggered
     * when the middleware is called
     *
     * @return mixed
     */
    public function handle(): bool
    {
        if (!auth()->check()) {
            return $this->failed();
        }

        return true;
    }

    /**
     * @return void|null
     */
    protected function failed()
    {
        return redirect('login');
    }
}
