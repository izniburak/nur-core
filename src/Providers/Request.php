<?php

namespace Nur\Providers;

use Nur\Kernel\ServiceProvider;
use Nur\Http\Request as BaseRequest;
use Nur\Http\Validation;

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
        // Request
        $this->app->singleton(BaseRequest::class, BaseRequest::class);

        // Validation
        $this->app->singleton(Validation::class, Validation::class);
    }
}
