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
     * @var void
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
     * @var Schema
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
        if ($config['driver'] == 'sqlite') {
            if (strpos($config['database'], ':') === false) {
                $config['database'] = realpath(ROOT . '/storage/database/' . $config['database']);
            }
        }

        $capsule->addConnection($config);
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
     * @return object
     */
    public static function getCapsule()
    {
        return self::$capsule;
    }

    /**
     * Get Eloquent Schema.
     *
     * @return object
     */
    public static function getSchema()
    {
        return self::$schema;
    }
}
