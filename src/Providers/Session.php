<?php

namespace Nur\Providers;

use Nur\Kernel\ServiceProvider;

class Session extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     * @throws
     */
    public function register()
    {
        session_start();
        $this->app->singleton('session', \Nur\Http\Session::class);
    }
}
