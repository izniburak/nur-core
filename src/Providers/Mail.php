<?php 

namespace Nur\Providers;

use Nur\Kernel\ServiceProvider;

class Mail extends ServiceProvider
{
  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register()
  {
    $this->app->set('mail', \Nur\Components\Mailer\Mailer::class);
  }
}
