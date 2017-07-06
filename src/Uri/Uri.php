<?php
/**
* nur - a simple framework for PHP Developers
*
* @author   izni burak demirtaş (@izniburak) <izniburak@gmail.com>
* @web      <http://burakdemirtas.org>
* @url      <https://github.com/izniburak/nur>
* @license  The MIT License (MIT) - <http://opensource.org/licenses/MIT>
*/

namespace Nur\Uri;

class Uri
{
    protected static $instance = null;

    /**
    * Call static function for Uri Class
    *
    * @return mixed
    */
    public static function __callStatic($method, $parameters)
    {
        if(empty($parameters))
            array_push($parameters, '');

        if(strpos($method, "secure") !== 0)
            return call_user_func_array([self::getInstance(), $method], $parameters);
        else
        {
            array_push($parameters, true);
            $methodName = strtolower( str_replace("secure", "", $method) );
            return call_user_func_array([self::getInstance(), $methodName], $parameters);
        }
    }

    /**
    * instance of Class.
    *
    * @return string | null
    */
    public static function getInstance()
    {
        if (null === self::$instance)
            self::$instance = new UriGenerator();

        return self::$instance;
    }
}
