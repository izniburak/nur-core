<?php

namespace Nur\Providers;

use Nur\Kernel\ServiceProvider;
use Illuminate\Events\Dispatcher;
use Illuminate\Contracts\Queue\Factory as QueueFactoryContract;

class Event extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('events', function ($app) {
            return (new Dispatcher($app))->setQueueResolver(function () use ($app) {
                return $app->make(QueueFactoryContract::class);
            });
        });
    }
}
