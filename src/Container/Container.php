<?php

namespace Nur\Container;

use Nur\Exception\ExceptionHandler;
use Pimple\Container as BaseContainer;

class Container implements ContainerInterface
{
    /**
     * The current globally available container (if any).
     *
     * @var static
     */
    protected static $instance;

    /**
     * Pimple Container
     *
     * @var \Pimple\Container
     */
    protected $container;

    /**
     * Registered services
     *
     * @var array
     */
    protected $services = [];

    /**
     * Constructor of Container
     *
     * @return void
     */
    public function __construct()
    {
        $this->container = new BaseContainer;
    }

    /**
     * Set a new service in Container
     *
     * @param string          $name
     * @param string|callable $service
     * @param array           $args
     *
     * @return void
     * @throws ExceptionHandler
     */
    public function set($name, $service, array $args = [])
    {
        $this->setService($name);
        $this->container[$name] = is_callable($service)
            ? $service
            : (is_string($service) && class_exists($service)
                ? function ($c) use ($service, $args) {
                    return new $service(...$args);
                }
                : $service
            );
    }

    /**
     * Check service is created?
     *
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        return is_null($name) ? false : in_array($name, $this->services);
    }

    /**
     * Get a created service if it's exists.
     *
     * @param string $name
     *
     * @return mixed|null
     */
    public function get($name = null)
    {
        return $this->has($name) ? $this->container[$name] : null;
    }

    /**
     * Set a new service in Container as factory
     *
     * @param string          $name
     * @param string|callable $service
     * @param array           $args
     *
     * @return void
     * @throws
     */
    public function factory($name, $service, array $args = [])
    {
        $this->setService($name);
        $factory = is_callable($service)
            ? $service
            : function ($c) use ($service, $args) {
                return new $service(...$args);
            };

        $this->container[$name] = $this->container->factory($factory);
    }

    /**
     * Extends a exists service.
     *
     * @return void
     */
    public function extend($name, $func)
    {
        $this->container->extend($name, $func);
    }

    /**
     * Set a new service in Container as protect
     *
     * @return void
     * @throws
     */
    public function protect($name, $service)
    {
        $this->setService($name);
        $this->container[$name] = $this->container->protect($service);
    }

    /**
     * Get base service container
     *
     * @return \Pimple\Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Set the globally available instance of the container.
     *
     * @return \Nur\Container\Container
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * Set the shared instance of the container.
     *
     * @param  \Nur\Container\ContainerInterface $container
     *
     * @return \Nur\Container\ContainerInterface
     */
    public static function setInstance(ContainerInterface $container)
    {
        return static::$instance = $container;
    }

    /**
     * Add a new service in service list in Container if it's not exists
     *
     * @param string $name
     *
     * @return int
     * @throws
     */
    protected function setService($name)
    {
        if ($this->has($name)) {
            throw new ExceptionHandler('Service already defined. (' . $name . ')');
        }

        return array_push($this->services, $name);
    }
}
