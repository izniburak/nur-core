<?php 

namespace Nur\Providers;

use Nur\Kernel\ServiceProvider;

class Upload extends ServiceProvider
{
  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register()
  {
    $this->app->set('upload', \Nur\Components\Upload\Upload::class);
  }
}
