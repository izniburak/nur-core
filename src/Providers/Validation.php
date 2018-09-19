<?php

namespace Nur\Providers;

use Nur\Kernel\ServiceProvider;

class Validation extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     * @throws
     */
    public function register()
    {
        $this->app->set('validation', \Nur\Components\Validation\Validation::class);
    }
}
