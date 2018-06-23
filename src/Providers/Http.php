<?php 

namespace Nur\Providers;

use Nur\Kernel\ServiceProvider;

class Http extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->set('http', \Nur\Http\Http::class);
        $this->app->set('session', \Nur\Http\Session::class);
        $this->app->set('cookie', \Nur\Http\Cookie::class);
    }
}
