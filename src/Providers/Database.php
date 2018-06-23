<?php 

namespace Nur\Providers;

use Nur\Kernel\ServiceProvider;

class Database extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->set('sql', \Nur\Database\Sql::class);
        $this->app->set('builder', \Nur\Database\Builder::class);
    }
}
