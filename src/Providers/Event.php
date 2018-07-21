<?php 

namespace Nur\Providers;

use Nur\Kernel\ServiceProvider;

class Event extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->set('event', \Nur\Event\Event::class);
    }
}