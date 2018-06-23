<?php 

namespace Nur\Providers;

use Nur\Kernel\ServiceProvider;

class Uri extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->set('uri', \Nur\Uri\Uri::class);
    }
}
