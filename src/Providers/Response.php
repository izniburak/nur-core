<?php 

namespace Nur\Providers;

use Nur\Kernel\ServiceProvider;

class Response extends ServiceProvider
{
  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register()
  {
    $this->app->set('response', \Nur\Http\Response::class);
  }
}
