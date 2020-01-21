<?php

namespace Nur\Providers;

use Nur\Kernel\ServiceProvider;
use Nur\Mail\Mail as NurMail;

class Mail extends ServiceProvider
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
        $this->app->singleton('mail', function ($app) {
            $config = $app['config']['mail'];
            return new NurMail($config, false);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return ['mail'];
    }
}
