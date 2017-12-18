<?php
/**
* nur - a simple framework for PHP Developers
*
* @author   izni burak demirtaş (@izniburak) <izniburak@gmail.com>
* @web      <http://burakdemirtas.org>
* @url      <https://github.com/izniburak/nur>
* @license  The MIT License (MIT) - <http://opensource.org/licenses/MIT>
*/
 
namespace Nur\Router;

use Nur\Router\RouterCommand;
use Nur\Router\RouterException;
use Buki\Router as RouterProvider;

class Router extends RouterProvider
{
    /**
	 * Throw new Exception for Router Error
	 *
     * @param string $message
	 * @return RouterException
	 */
	public function exception($message = '')
	{
		return new RouterException($message);
	}

    /**
	 * RouterCommand class
	 *
     * @param string $message
	 * @return RouterCommand
	 */
	public function routerCommand($message = '')
	{
		return new RouterCommand($message);
	}
}
