<?php

namespace Nur\Providers;

use Nur\Kernel\ServiceProvider;

class Request extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     * @throws
     */
    public function register()
    {
        $this->app->singleton('request', \Nur\Http\Request::class);
    }
}
