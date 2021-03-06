<?php

namespace Nur\Providers;

use Nur\Kernel\ServiceProvider;

class Load extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     * @throws
     */
    public function register()
    {
        $this->app->singleton('load', \Nur\Load\Load::class);
    }
}
