<?php

namespace Nur\Providers;

use Nur\Kernel\ServiceProvider;

class Auth extends ServiceProvider
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
    public function register(): void
    {
        $this->app->singleton(\Nur\Auth\Auth::class, \Nur\Auth\Auth::class);

        if ($this->app->get('config')['auth']['jwt']['enabled'] === true) {
            $this->app->singleton('jwt', \Nur\Auth\Jwt\Jwt::class);
        }
    }
}
