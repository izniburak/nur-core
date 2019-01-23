<?php

namespace Nur\Providers;

use Nur\Kernel\ServiceProvider;

class Response extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     * @throws
     */
    public function register()
    {
        $this->app->singleton('response', \Nur\Http\Response::class);
    }
}
