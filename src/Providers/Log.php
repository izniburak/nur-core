<?php

namespace Nur\Providers;

use Nur\Kernel\ServiceProvider;

class Log extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     * @throws
     */
    public function register()
    {
        $this->app->set('log', \Nur\Log\Log::class);
    }
}
