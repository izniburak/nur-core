<?php

namespace Nur\Database;

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Builder;
use Illuminate\Events\Dispatcher;

/**
 * Class Eloquent
 * Adapted from illuminate/database package of Laravel
 * @see https://github.com/laravel/framework/tree/8.x/src/Illuminate/Database
 *
 * @package Nur\Database
 */
class Eloquent
{
    /**
     * Class instance
     *
     * @var null|Eloquent
     */
    protected static $instance = null;

    /**
     * Capsule
     *
     * @var Capsule
     */
    protected static $capsule = null;

    /**
     * Schema
     *
     * @var \Illuminate\Database\Schema\Builder
     */
    protected static $schema = null;

    /**
     * Create Eloquent Capsule.
     */
    function __construct()
    {
        $capsule = new Capsule;
        $config = config('database');
        $activeDb = $config['connections'][$config['default']];
        if ($activeDb['driver'] === 'sqlite') {
            $activeDb['database'] = database_path($activeDb['database']);
        }

        $capsule->addConnection($activeDb);
        // Set the event dispatcher used by Eloquent models... (optional)
        // [TODO] - use dependency container
        $capsule->setEventDispatcher(new Dispatcher(new Container));
        // Make this Capsule instance available globally via static methods... (optional)
        $capsule->setAsGlobal();
        // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
        $capsule->bootEloquent();

        self::$capsule = $capsule;
        self::$schema = $capsule->schema();
    }

    public static function getInstance(): ?Eloquent
    {
        if (null === self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public static function getCapsule(): ?Capsule
    {
        return self::$capsule;
    }

    public static function getSchema(): ?Builder
    {
        return self::$schema;
    }
}
