<?php

use Nur\Container\Container;
use Phpmig\Adapter;

$container = app()->make(Container::class);
$config = config('database');

$container->instance('db.config', $config);
$container->singleton('phpmig.eloquent', function() {
    return new \Nur\Database\Eloquent();
});
$container->singleton('db', function ($c) {
    $capsule = $c['phpmig.eloquent']->getCapsule();
    $capsule->getContainer()->singleton(
        \Illuminate\Contracts\Debug\ExceptionHandler::class
    /* \Your\ExceptionHandler\Implementation::class */
    );

    return $capsule;
});

$container->singleton('phpmig.adapter', function ($c) {
    return new Adapter\Illuminate\Database($c['db'], $c['db.config']['migrations']);
});

$container->instance('phpmig.migrations_path', database_path('migrations'));
$container->singleton('schema', function ($c) {
    return $c['db']->schema();
});

return $container;
