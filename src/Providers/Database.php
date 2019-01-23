<?php

namespace Nur\Providers;

use Nur\Kernel\ServiceProvider;
use Illuminate\Pagination\Paginator;

class Database extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     * @throws
     */
    public function register()
    {
        $this->app->singleton('builder', \Nur\Database\Builder::class);

        // Paginator::viewFactoryResolver(function () {
        //     return $this->app['view'];
        // });
        Paginator::currentPathResolver(function () {
            return $this->app['request']->url();
        });
        Paginator::currentPageResolver(function ($pageName = 'page') {
            $page = $this->app['request']->input($pageName);
            if (filter_var($page, FILTER_VALIDATE_INT) !== false && (int) $page >= 1) {
                return (int) $page;
            }
            return 1;
        });
    }
}
