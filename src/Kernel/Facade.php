<?php

namespace Nur\Kernel;

use Closure;
use RuntimeException;

/**
 * Class Facade
 * Adapted from Laravel Framework
 * @see https://github.com/laravel/framework/blob/6.x/src/Illuminate/Support/Facades/Facade.php
 *
 * @package Nur\Kernel
 */
abstract class Facade
{
    /**
     * Application List in Service Provider
     *
     * @var Application
     */
    protected static $app;

    /**
     * Resolved instances of objects in Facade
     *
     * @var array
     */
    protected static $resolvedInstance;

    /**
     * Set Facade Application (Container)
     *
     * @param string $app
     *
     * @return void
     */
    public static function setFacadeApplication($app): void
    {
        static::$app = $app;
    }

    /**
     * Get the application instance behind the facade.
     *
     * @return Application
     */
    public static function getFacadeApplication(): Application
    {
        return static::$app;
    }

    /**
     * Clear Resolved Instance
     *
     * @param string $name
     *
     * @return void
     */
    public static function clearResolvedInstance($name): void
    {
        unset(static::$resolvedInstance[$name]);
    }

    /**
     * Clear All Resolved Instances
     *
     * @return void
     */
    public static function clearResolvedInstances(): void
    {
        static::$resolvedInstance = [];
    }

    /**
     * Run a Closure when the facade has been resolved.
     *
     * @param Closure $callback
     *
     * @return void
     */
    public static function resolved(Closure $callback): void
    {
        $accessor = static::getFacadeAccessor();

        if (static::$app->resolved($accessor) === true) {
            $callback(static::getFacadeRoot());
        }

        static::$app->afterResolving($accessor, function ($service) use ($callback) {
            $callback($service);
        });
    }

    /**
     * Hotswap the underlying instance behind the facade.
     *
     * @param mixed $instance
     *
     * @return void
     */
    public static function swap($instance): void
    {
        static::$resolvedInstance[static::getFacadeAccessor()] = $instance;

        if (isset(static::$app)) {
            static::$app->instance(static::getFacadeAccessor(), $instance);
        }
    }

    /**
     * Get the root object behind the facade.
     *
     * @return mixed
     */
    public static function getFacadeRoot()
    {
        return static::resolveFacadeInstance(static::getFacadeAccessor());
    }

    /**
     * Call Methods in Application Object
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        $instance = static::getFacadeRoot();

        if (! $instance) {
            throw new RuntimeException('A facade root has not been set.');
        }

        return $instance->$method(...$args);
    }

    /**
     * Call Methods in Application Object
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        return self::__callStatic($method, $args);
    }

    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        throw new RuntimeException('Facade does not implement getFacadeAccessor method.');
    }

    /**
     * Resolved Instance
     *
     * @param object|string $name
     *
     * @return mixed|void
     */
    protected static function resolveFacadeInstance($name)
    {
        if (is_object($name)) {
            return $name;
        }

        if (isset(static::$resolvedInstance[$name])) {
            return static::$resolvedInstance[$name];
        }

        if (static::$app) {
            return static::$resolvedInstance[$name] = static::$app[$name];
        }
    }
}
