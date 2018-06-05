<?php 

namespace Nur\Providers;

use Nur\Kernel\ServiceProvider;

class Log extends ServiceProvider
{
  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register()
  {
    $this->app->set('log', \Nur\Log\Log::class);
  }
}
