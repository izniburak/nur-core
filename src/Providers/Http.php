<?php

namespace Nur\Providers;

use Nur\Kernel\ServiceProvider;

class Http extends ServiceProvider
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
        $this->app->singleton('http', \Nur\Http\Http::class);
    }
}
