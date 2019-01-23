<?php

namespace Nur\Providers;

use Nur\Kernel\ServiceProvider;

class Html extends ServiceProvider
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
        $this->app->singleton('html', \Nur\Components\Builder\Html::class);
        $this->app->singleton('form', \Nur\Components\Builder\Form::class);
    }
}
