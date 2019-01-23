<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Nur\Container\Container;
use Phpmig\Adapter;

$container = Container::getInstance();
$config = config('database');

$container->instance('db.config', $config);
$container->singleton('db', function ($c) {
    $capsule = new Capsule();
    $capsule->getContainer()->singleton(
        \Illuminate\Contracts\Debug\ExceptionHandler::class
        /* \Your\ExceptionHandler\Implementation::class */
    );

    $defaultDriver = $c['db.config']['default'];
    $activeDb = $c['db.config']['connections'][$defaultDriver];
    if ($activeDb['driver'] === 'sqlite') {
        $activeDb['database'] = database_path($activeDb['database']);
    }

    $capsule->addConnection($activeDb);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
});

$container->singleton('phpmig.adapter', function ($c) {
    return new Adapter\Illuminate\Database($c['db'], $c['db.config']['migrations']);
});

$container->instance('phpmig.migrations_path', database_path('migrations'));
$container->instance('schema', function ($c) {
    return $c['db']->schema();
});

return $container;
