<?php
/**
* nur - a simple framework for PHP Developers
*
* @author   izni burak demirtaş (@izniburak) <izniburak@gmail.com>
* @web      <http://burakdemirtas.org>
* @url      <https://github.com/izniburak/nur>
* @license  The MIT License (MIT) - <http://opensource.org/licenses/MIT>
*/

namespace Nur\Http;

class Cookie
{
    public function __construct() { }

    /**
    * Set cookie method. 
    * @param 	string $key
    * @param 	string $value
    * @param 	integer $time
    * @return   null
    */
    public static function set($key, $value, $time = 0)
    {
        if(is_array($key))
        {
            foreach ($key as $k => $v)
            {
                setcookie($k, $v, ($time == 0 ? 0 : time() + $time), '/');
                $_COOKIE[$k] = $v;
            }
        }
        else
        {
            setcookie($key, $value, ($time == 0 ? 0 : time() + $time), '/');
            $_COOKIE[$key] = $value;
        }

        return;
    }

    /**
    * Get cookie method.
    * @param 	string $key
    * @return   null | mixed
    */
    public static function get($key = null)
    {
        return (is_null($key) ? $_COOKIE : ( self::hasKey($key) ? $_COOKIE[$key] : null) );
    }

    /**
    * Cookie has key ? 
    * @param 	string $key
    * @return 	bool
    */
    public static function hasKey($key)
	{
		return isset($_COOKIE[$key]);
	}

    /**
    * Delete cookie method.
    * @param 	string $key
    * @return   null
    */
    public static function delete($key)
    {
        if( self::hasKey($key) )
        {
            setcookie($key, null, -1, '/');
            unset($_COOKIE[$key]);
        }

        return;
    }

    /**
    * Delete all cookie method. 
    *
    * @return null
    */
    public static function destroy()
    {
        foreach ($_COOKIE as $k => $v)
        {
            setcookie($k, null, -1, '/');
            unset($_COOKIE[$k]);
        }

        return;
    }
}
