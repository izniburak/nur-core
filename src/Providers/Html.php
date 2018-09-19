<?php

namespace Nur\Providers;

use Nur\Kernel\ServiceProvider;

class Html extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     * @throws
     */
    public function register()
    {
        $this->app->set('html', \Nur\Components\Builder\Html::class);
        $this->app->set('form', \Nur\Components\Builder\Form::class);
    }
}
