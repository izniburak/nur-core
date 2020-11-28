<?php

namespace Nur\Providers;

use Nur\Kernel\ServiceProvider;

class Cookie extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     * @throws
     */
    public function register()
    {
        $this->app->singleton(\Nur\Http\Cookie::class, \Nur\Http\Cookie::class);
    }
}
