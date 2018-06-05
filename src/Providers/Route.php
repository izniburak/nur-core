<?php 

namespace Nur\Providers;

use Nur\Kernel\ServiceProvider;

class Route extends ServiceProvider
{
  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register()
  {
    $this->app->set('route', \Nur\Router\Route::class);
  }
}
