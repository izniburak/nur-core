<?php

namespace Nur\Kernel;

use Closure;
use RuntimeException;

abstract class Facade
{
    /**
     * Application List in Service Provider
     *
     * @var array
     */
    protected static $app;

    /**
     * Resolved instances of objects in Facade
     *
     * @var array
     */
    protected static $resolvedInstance;

    /**
     * Created instances of objects in Facade
     *
     * @var array
     */
    protected static $createdInstances = [];

    /**
     * Set Facade Application (Container)
     *
     * @param string $app
     *
     * @return void
     */
    public static function setApplication($app): void
    {
        static::$app = $app;
    }

    /**
     * Get the application instance behind the facade.
     *
     * @return \Nur\Kernel\Application
     */
    public static function getFacadeApplication()
    {
        return static::$app;
    }

    /**
     * Clear Resolved Instance
     *
     * @param string $facadeName
     *
     * @return void
     */
    public static function clearResolvedInstance($facadeName): void
    {
        unset(static::$resolvedInstance[$facadeName]);
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
        static::$app->afterResolving(static::getFacadeAccessor(), function ($service) use ($callback) {
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
     * @param array  $parameters
     *
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        $accessor = strtolower(static::getFacadeAccessor());
        $provider = static::resolveInstance(strtolower($accessor));

        if (! array_key_exists($accessor, static::$createdInstances)) {
            static::$createdInstances[$accessor] = $provider;
        }

        return call_user_func_array([static::$createdInstances[$accessor], $method], $parameters);
    }

    /**
     * Call Methods in Application Object
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return self::__callStatic($method, $parameters);
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
     * @param string $facadeName
     *
     * @return string
     */
    protected static function resolveInstance($facadeName)
    {
        if (is_object($facadeName)) {
            return $facadeName;
        }

        if (isset(static::$resolvedInstance[$facadeName])) {
            return static::$resolvedInstance[$facadeName];
        }

        return static::$resolvedInstance[$facadeName] = static::$app->get($facadeName);
    }
}
