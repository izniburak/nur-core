<?php

namespace Nur\Providers;

use Nur\Kernel\ServiceProvider;

class Cache extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     * @throws
     */
    public function register()
    {
        $this->app->set('cache', \Nur\Components\Cache\Cache::class);
    }
}
