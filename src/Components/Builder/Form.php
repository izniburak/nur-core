<?php
/**
* nur - a simple framework for PHP Developers
*
* @author   izni burak demirtaş (@izniburak) <izniburak@gmail.com>
* @web      <http://burakdemirtas.org>
* @url      <https://github.com/izniburak/nur>
* @license  The MIT License (MIT) - <http://opensource.org/licenses/MIT>
*/

namespace Nur\Components\Builder;

use Nur\Uri\Uri;
use Nur\Components\Builder\Providers\FormProvider;

class Form
{
    protected static $instance = null;

    /**
    * Call static function for Form Class
    *
    * @return mixed
    */
    public static function __callStatic($method, $parameters)
    {
        return call_user_func_array([self::getInstance(), $method], $parameters);
    }

    /**
    * instance of Class.
    *
    * @return string | null
    */
    public static function getInstance()
    {
        if (null === self::$instance)
            self::$instance = new FormProvider( Uri::getInstance(), csrfToken() );

        return self::$instance;
    }
}
