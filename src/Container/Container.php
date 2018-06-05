<?php 
/**
* nur - a simple framework for PHP Developers
*
* @author   izni burak demirtaş (@izniburak) <izniburak@gmail.com>
* @web      <http://burakdemirtas.org>
* @url      <https://github.com/izniburak/nur>
* @license  The MIT License (MIT) - <http://opensource.org/licenses/MIT>
*/

namespace Nur\Container;

use Pimple\Container as BaseContainer;
use Nur\Exception\ExceptionHandler;

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
   * @return static
   */
  public function __construct()
  {
    $this->container = new BaseContainer;
  }

  /**
   * Set a new service in Container
   *
   * @return void
   */
  public function set($name, $service, $args = [])
  {
    $this->setService($name);
    $this->container[$name] = is_callable($service)
      ? $service 
      : (is_string($service) && class_exists($service)
          ? function($c) use ($service, $args) {
              return new $service(...$args);
            }
          : $service
        );
  }

  /**
   * Get a created service if it's exists.
   *
   * @return mixed|null
   */
  public function get($name = null) 
  {
    return $this->has($name) ? $this->container[$name] : null;
  }

  /**
   * Check service is created?
   *
   * @return bool
   */
  public function has($name)
  {
    return is_null($name) ? false : in_array($name, $this->services);
  }

  /**
   * Set a new service in Container as factory
   *
   * @return void
   */
  public function factory($name, $service, $args = [])
  {
    $this->setService($name);
    $factory = is_callable($service)
      ? $service 
      : function($c) use ($service, $args) {
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
   */
  public function protect($name, $service)
  {
    $this->setService($name);
    $this->container[$name] = $this->container->protect($service);
  }

  /**
   * Add a new service in service list in Container 
   * if it's not exists
   *
   * @return void
   */
  protected function setService($name)
  {
    if($this->has($name)) {
      throw new ExceptionHandler('Service already defined. ('.$name.')');
    }

    return array_push($this->services, $name);
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
   * @param  \Nur\Container\Container|null  $container
   * @return \Nur\Container\Container|static
   */
  public static function setInstance(ContainerInterface $container = null)
  {
    return static::$instance = $container;
  }
}
