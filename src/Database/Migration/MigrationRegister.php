<?php

use Phpmig\Adapter;
use Nur\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;

$container = Container::getInstance();
$config = config('database');

if ($config['driver'] === 'sqlite') {
    if (strpos($config['database'], ':') === false) {
        $config['database'] = realpath(ROOT . '/storage/database/'. $config['database']);
    }
}

$container->set('db.config', $config);

$container->set('db', function ($c) {
    $capsule = new Capsule();
    $capsule->getContainer()->singleton(
        \Illuminate\Contracts\Debug\ExceptionHandler::class
        /* \Your\ExceptionHandler\Implementation::class */
    );
    $capsule->addConnection($c['db.config']);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
});

$container->set('phpmig.adapter', function($c) {
    return new Adapter\Illuminate\Database($c['db'], 'nur_migrations');
});

$container->set('phpmig.migrations_path', realpath('app/Migrations'));
$container->set('schema', function($c) {
    return $c['db']->schema();
});

return $container->getContainer();
