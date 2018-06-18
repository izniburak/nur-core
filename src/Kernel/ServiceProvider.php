<?php 
/**
* nur - a simple framework for PHP Developers
*
* @author   izni burak demirtaş (@izniburak) <izniburak@gmail.com>
* @web      <http://burakdemirtas.org>
* @url      <https://github.com/izniburak/nur>
* @license  The MIT License (MIT) - <http://opensource.org/licenses/MIT>
*/

namespace Nur\Kernel;

abstract class ServiceProvider
{
  /**
   * The application instance.
   *
   * @var \Nur\Container\Container
   */
  protected $app;

  /**
   * Indicates if loading of the provider is deferred.
   *
   * @var bool
   */
  protected $defer = false;

  /**
   * Create a new service provider instance.
   *
   * @return void
   */
  public function __construct($app)
  {
    $this->app = $app;
  }

  /**
   * Get the services provided by the provider.
   *
   * @return array
   */
  public function provides()
  {
    return [];
  }

  /**
   * Get the events that trigger this service provider to register.
   *
   * @return array
   */
  public function when()
  {
    return [];
  }

  /**
   * Determine if the provider is deferred.
   *
   * @return bool
   */
  public function isDeferred()
  {
    return $this->defer;
  }
}
