<?php
/**
* nur - a simple framework for PHP Developers
*
* @author   izni burak demirtaş (@izniburak) <izniburak@gmail.com>
* @web      <http://burakdemirtas.org>
* @url      <https://github.com/izniburak/nur>
* @license  The MIT License (MIT) - <http://opensource.org/licenses/MIT>
*/

namespace Nur\Database;

use Buki\Pdox as QueryProvider;

class Sql
{
    /**
    * Class instance variable
    */
    private static $instance = null;

    /**
    * Call static function for Pdox Class
    *
    * @return mixed
    */
    public static function __callStatic($method, $parameters)
    {
        return call_user_func_array([self::getInstance(), $method], $parameters);
    }

    /**
    * Get class instance
    *
    * @return PdoxObject
    */
    public static function getInstance()
    {
        if (null === self::$instance)
        {
            $debug = (APP_MODE == 'dev' ? true : false);

            $config = config('db');
            $config['cachedir'] = realpath(ROOT . '/storage/cache/sql/');
            if($config['driver'] == "sqlite")
                $config['database'] = realpath(ROOT . '/storage/database/'. $config['database']);
            $config['debug'] = $debug;

            self::$instance = new QueryProvider($config);
        }

        return self::$instance;
    }
}
