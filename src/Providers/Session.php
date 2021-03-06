<?php

namespace Nur\Providers;

use Nur\Kernel\ServiceProvider;

class Session extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     * @throws
     */
    public function register()
    {
        $this->app->singleton(\Nur\Http\Session::class, \Nur\Http\Session::class);
    }
}
