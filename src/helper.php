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

### get the available container instance
if (!function_exists('app')) {
    function app($name = null)
    {
        if (is_null($name)) {
            return Container::getInstance();
        }

        return Container::getInstance()->get($name);
    }
}

### get config values function
if (!function_exists('config')) {
    function config($param = null)
    {   
        $config = app('config');
        
        if(is_null($param)) {
            return $config;
        }

        if(!strstr($param, '.')) {
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

### Logger function
if (!function_exists('logger')) {
    function logger($message = null)
    {
        if (is_null($message)) {
            return app('log');
        }

        return app('log')->debug($message);
    }
}

### Blade::make function
if (!function_exists('blade')) {
    function blade($view = null, $data = [], $mergeData = [])
    {
        return app('blade')->make($view, $data, $mergeData);
    }
}

### Load::view function
if (!function_exists('view')) {
    function view($name, $data = null)
    {
        return app('load')->view($name, $data);
    }
}

### Error::message function
if (!function_exists('error')) {
    function error($title = null, $msg = null, $page = null)
    {
        return app('load')->error($title, $msg, $page);
    }
}

### session 
if (!function_exists('session')) {
    function session($name = null)
    {
        if (is_null($name)) {
            return app('session');
        }

        return app('session')->get($name);
    }
}

### cookie
if (!function_exists('cookie')) {
    function cookie($name = null)
    {
        if (is_null($name)) {
            return app('cookie');
        }

        return app('cookie')->get($name);
    }
}

### application uri
if (!function_exists('uri')) {
    function uri($name = null)
    {
        if (is_null($name)) {
            return app('uri');
        }

        return app('uri')->base($name);
    }
}

### http
if (!function_exists('http')) {
    function http($name = null)
    {
        if (is_null($name)) {
            return app('http');
        }

        return app('http')->request($name);
    }
}

### token generator function
if (!function_exists('getToken')) {
    function getToken()
    {
        return _TOKEN;
    }
}

### token reset function
if (!function_exists('resetToken')) {
    function resetToken()
    {
        if(app('session')->hasKey('_nur_token')) {
            app('session')->delete('_nur_token');
        }
    }
}

/**
 * CSRF Token Generate
 * @return string
 */
if (!function_exists('csrfToken')) {
    function csrfToken($name = null)
    {
        $csrf = hash_hmac('sha256', getToken(), uniqid('', true));
        app('session')->set('_nur_csrf_token' . (!is_null($name) ? '_' . $name : ''), $csrf);

        return $csrf;
    }
}

/**
 * CSRF Token Check
 * @param $token
 * @return boolean
 */
if (!function_exists('csrfCheck')) {
    function csrfCheck($token, $name = null)
    {
        $session = app('session');
        $name = (!is_null($name) ? '_' . $name : '');
        if ($session->hasKey('_nur_csrf_token' . $name) && $token === $session->get('_nur_csrf_token' . $name)) {
            $session->delete('_nur_csrf_token' . $name);
            return true;
        }

        return false;
    }
}

### dd function
if (!function_exists('dd')) {
    function dd(...$args)
    {
        foreach ($args as $x) {
            var_dump($x);
        }

        die(1);
    }
}


### Escape HTML special characters in a string.
if (!function_exists('e')) {
    function e(string $value, $doubleEncode = true)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', $doubleEncode);
    }
}
