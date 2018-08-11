<?php
/**
* nur - a simple framework for PHP Developers
*
* @author   izni burak demirtaş (@izniburak) <izniburak@gmail.com>
* @web      <http://burakdemirtas.org>
* @url      <https://github.com/izniburak/nur>
* @license  The MIT License (MIT) - <http://opensource.org/licenses/MIT>
*/

use Nur\Container\Container;

/**
 * Get application container or a service.
 * @return mixed|Nur\Container\Container
 */
if (! function_exists('app')) {
    function app($name = null)
    {
        if (is_null($name)) {
            return Container::getInstance();
        }

        return Container::getInstance()->get($name);
    }
}

/**
 * Get config values
 * @return mixed
 */
if (! function_exists('config')) {
    function config($param = null)
    {   
        $config = app('config');
        
        if (is_null($param)) {
            return $config;
        }

        if (! strstr($param, '.')) {
            return $config[$param];
        }

        $value = $config;
        foreach (explode('.', $param) as $index) {
            if (key_exists($index, $value)) {
                $value = $value[$index];
            } else {
                return null;
            }
        }

        return $value;
    }
}

/**
 * Logger
 * @return mixed|Nur\Log\Log
 */
if (! function_exists('logger')) {
    function logger($message = null)
    {
        if (is_null($message)) {
            return app('log');
        }

        return app('log')->debug($message);
    }
}

/**
 * Blade template engine
 * @return string
 */
if (! function_exists('blade')) {
    function blade($view = null, Array $data = [], Array $mergeData = [])
    {
        return app('blade')->make($view, $data, $mergeData);
    }
}

/**
 * View template
 * @return string
 */
if (! function_exists('view')) {
    function view($name, Array $data = [])
    {
        return app('load')->view($name, $data);
    }
}

/**
 * Error messages as view
 * @return string
 */
if (! function_exists('error')) {
    function error($title = null, $msg = null, $page = null)
    {
        return app('load')->error($title, $msg, $page);
    }
}

/**
 * Sessions
 * @return mixed|Nur\Http\Session
 */
if (! function_exists('session')) {
    function session($name = null)
    {
        if (is_null($name)) {
            return app('session');
        }

        return app('session')->get($name);
    }
}

/**
 * Cookies 
 * @return mixed|Nur\Http\Cookie
 */
if (! function_exists('cookie')) {
    function cookie($name = null)
    {
        if (is_null($name)) {
            return app('cookie');
        }

        return app('cookie')->get($name);
    }
}

/**
 * Uri class
 * @return string|Nur\Uri\Uri
 */
if (! function_exists('uri')) {
    function uri($name = null)
    {
        if (is_null($name)) {
            return app('uri');
        }

        return app('uri')->base($name);
    }
}

/**
 * Http methods
 * @return mixed|Nur\Http\Http
 */
if (! function_exists('http')) {
    function http($name = null)
    {
        if (is_null($name)) {
            return app('http');
        }

        return app('http')->request($name);
    }
}

/**
 * Event trigger for Listeners.
 * @return mixed
 */
if (! function_exists('event')) {
    function event($event, Array $params = [], $method = 'handle')
    {
        return app('event')->trigger($event, $params, $method);
    }
}

/**
 * Get Application token
 * @return string
 */
if (! function_exists('getToken')) {
    function getToken()
    {
        return _TOKEN;
    }
}

/**
 * Application token reset
 * @return void
 */
if (! function_exists('resetToken')) {
    function resetToken()
    {
        if (session()->hasKey('_nur_token')) {
            session()->delete('_nur_token');
        }
    }
}

/**
 * CSRF Token Generate
 * @return string
 */
if (! function_exists('csrfToken')) {
    function csrfToken($name = null)
    {
        $csrf = hash_hmac('sha256', getToken(), uniqid('', true));
        session()->set('_nur_csrf_token' . (!is_null($name) ? '_' . $name : ''), $csrf);

        return $csrf;
    }
}

/**
 * CSRF Token Check
 * @param $token
 * @return boolean
 */
if (! function_exists('csrfCheck')) {
    function csrfCheck($token, $name = null)
    {
        $session = session();
        $name = (!is_null($name) ? '_' . $name : '');
        if ($session->hasKey('_nur_csrf_token' . $name) && $token === $session->get('_nur_csrf_token' . $name)) {
            $session->delete('_nur_csrf_token' . $name);
            return true;
        }

        return false;
    }
}

/**
 * Dump and Die function 
 * @param ...$args
 * @return string
 */
if (! function_exists('dd')) {
    function dd(...$args)
    {
        foreach ($args as $arg) {
            var_dump($arg);
        }

        die(1);
    }
}


### Escape HTML special characters in a string.
if (! function_exists('e')) {
    function e(string $value, $doubleEncode = true)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', $doubleEncode);
    }
}
