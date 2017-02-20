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

use Nur\Uri\Uri;

class Session
{
    public function __construct() { }

    /**
    * Set session method. 
    * @param 	string $key
    * @param 	string $value
    * @return   null
    */
    public static function set($key, $value)
    {
        if(is_array($key))
            foreach ($key as $k => $v)
                $_SESSION[$k] = $v;
        else
            $_SESSION[$key] = $value;

        return;
    }

    /**
    * Get session method. 
    * @param 	string $key
    * @return   null | mixed
    */
    public static function get($key = null)
    {
        return (is_null($key) ? $_SESSION : ( self::hasKey($key) ? $_SESSION[$key] : null) );
    }

    /**
    * Session has key ? 
    * @param 	string $key
    * @return 	bool
    */
    public static function hasKey($key)
	{
		return isset($_SESSION[$key]);
	}

    /**
    * Setting Flash Message
    * @param 	string $key
    * @param 	string $value
    * @param 	string $redirect
    * @return 	bool
    */
	public static function setFlash($key, $value, $redirect = null)
	{
		self::set('_nur_flash', [$key => $value]);
		if (!is_null($redirect)) 
			uri::redirect($redirect);

		return true;
	}

    /**
    * Getting Flash Message
    * @param 	string $key
    * @return   null | string
    */
	public static function getFlash($key = null)
	{
        if(!is_null($key))
        {
            $value = null;
            if(self::hasFlash($key))
            {
                $value = self::get('_nur_flash')[$key];
                unset($_SESSION['_nur_flash'][$key]);
            }
		    
            return $value;
        }
        else 
            return $key;
	}

    /**
    * Session has flash key ? 
    * @param 	string $key
    * @return 	bool
    */
    public static function hasFlash($key)
	{
		return isset($_SESSION['_nur_flash'][$key]);
	}

    /**
    * Delete session method. 
    * @param 	string $key
    * @return   null
    */
    public static function delete($key)
    {
        if( self::hasKey($key) )
            unset($_SESSION[$key]);

        return;
    }

    /**
    * Delete all session method. 
    *
    * @return null
    */
    public static function destroy()
    {
        $_SESSION = [];
        session_destroy();
        return;
    }

    /**
    * Get Session ID 
    *
    * @return string; session id
    */
    public static function id()
    {
        return session_id();
    }
}
