<?php 

namespace Nur\Providers;

use Nur\Kernel\ServiceProvider;

class Hash extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->set('hash', \Nur\Hash\Hash::class);
    }
}
