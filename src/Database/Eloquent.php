<?php

namespace Nur\Database;

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;

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
     *
     * @return void
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
        $capsule->setEventDispatcher(new Dispatcher(new Container));
        // Make this Capsule instance available globally via static methods... (optional)
        $capsule->setAsGlobal();
        // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
        $capsule->bootEloquent();

        self::$capsule = $capsule;
        self::$schema = $capsule->schema();
    }

    /**
     * instance of Class.
     *
     * @return Eloquent
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * Get Eloquent Capsule.
     *
     * @return Capsule
     */
    public static function getCapsule()
    {
        return self::$capsule;
    }

    /**
     * Get Eloquent Schema.
     *
     * @return \Illuminate\Database\Schema\Builder
     */
    public static function getSchema()
    {
        return self::$schema;
    }
}
