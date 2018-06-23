<?php 

namespace Nur\Providers;

use Nur\Kernel\ServiceProvider;

class Request extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->set('request', \Nur\Http\Request::class);
    }
}
