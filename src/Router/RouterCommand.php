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

use Nur\Router\RouterException;
use Buki\Router\RouterCommand as RouterCommandProvider;

class RouterCommand extends RouterCommandProvider
{
    /**
     * Throw new Exception for Router Error
     *
     * @param string $message
     * @return Nur\Router\RouterException
     */
    public function exception($message = '')
    {
        return new RouterException($message);
    }
}
