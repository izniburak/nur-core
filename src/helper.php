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
 * 
 * @param string|null $param
 * 
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
 * 
 * @param string|null $params
 * 
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
 * Throw an HttpException with the given data.
 *
 * @param  int     $code
 * @param  string  $message
 * @param  array   $headers
 * 
 * @return void
 */
if (! function_exists('abort')) {
    function abort($code, $message = '', array $headers = [])
    {

    }
}

/**
 * Throw an HttpException with the given data if the given condition is true.
 *
 * @param  bool    $boolean
 * @param  int     $code
 * @param  string  $message
 * @param  array   $headers
 * 
 * @return void
 */
if (! function_exists('abort_if')) {
    function abort_if($boolean, $code, $message = '', array $headers = [])
    {
        if ($boolean) {
            abort($code, $message, $headers);
        }
    }
}

/**
 * Logger
 * 
 * @param string|null $message
 * 
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
 * 
 * @param string $view
 * @param array $data 
 * @param array $mergeData
 * 
 * @return string
 */
if (! function_exists('blade')) {
    function blade($view, array $data = [], array $mergeData = [])
    {
        return app('blade')->make($view, $data, $mergeData);
    }
}

/**
 * View template
 * 
 * @param string $name 
 * @param array $data
 * 
 * @return string
 */
if (! function_exists('view')) {
    function view($name, array $data = [])
    {
        return app('load')->view($name, $data);
    }
}

/**
 * Error messages as view
 * 
 * @param string|null $title
 * @param string|null $message
 * @param string|null $page
 * 
 * @return string
 */
if (! function_exists('error')) {
    function error($title = null, $message = null, $page = null)
    {
        return app('load')->error($title, $message, $page);
    }
}

/**
 * Sessions
 * 
 * @param string|null $name
 * 
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
 * 
 * @param string|null $name
 * 
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
 * 
 * @param string|null $name
 * 
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
 * 
 * @param string|null $name
 * 
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
 * 
 * @param string $event 
 * @param array $params 
 * @param string $method
 * 
 * @return mixed
 */
if (! function_exists('event')) {
    function event($event, array $params = [], $method = 'handle')
    {
        return app('event')->trigger($event, $params, $method);
    }
}

/**
 * Get Application token
 * 
 * @return string
 */
if (! function_exists('get_token')) {
    function get_token()
    {
        return _TOKEN;
    }
}

/**
 * Application token reset
 * 
 * @return void
 */
if (! function_exists('reset_token')) {
    function reset_token()
    {
        if (session()->hasKey('_nur_token')) {
            session()->delete('_nur_token');
        }

        return;
    }
}

/**
 * CSRF Token Generate
 * 
 * @param string|null $name
 * 
 * @return string
 */
if (! function_exists('csrf_token')) {
    function csrf_token($name = null)
    {
        $csrf = hash_hmac('sha256', get_token(), uniqid('', true));
        session()->set('_nur_csrf_token' . (!is_null($name) ? '_' . $name : ''), $csrf);

        return $csrf;
    }
}

/**
 * CSRF Token Check
 * 
 * @param string $token
 * @param string|null $name
 * 
 * @return bool
 */
if (! function_exists('csrf_check')) {
    function csrf_check($token, $name = null)
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
 * CSRF Token Html Field
 * 
 * @param string|null $name
 * 
 * @return string
 */
if (! function_exists('csrf_field')) {
    function csrf_field(string $name = null)
    {
        return '<input type="hidden" name="_token" value="'.csrf_token($name).'" />';
    }
}

/**
 * Generate a form field to spoof the HTTP verb used by forms.
 * 
 * @param string $method
 * 
 * @return string
 */
if (! function_exists('method_field')) {
    function method_field(string $method)
    {
        return '<input type="hidden" name="_method" value="'.$method.'" />';
    }
}

/**
 * Get an instance of the current request or an input item from the request.
 *
 * @param  array|string  $key
 * @param  mixed   $default
 * 
 * @return \Nur\Http\Request|string|array
 */
if (! function_exists('request')) {
    function request($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('request');
        }
        if (is_array($key)) {
            return app('request')->only($key);
        }
        $value = app('request')->__get($key);
        return is_null($value) ? value($default) : $value;
    }
}

/**
 * Return a new response from the application.
 *
 * @param  string|array|null  $content
 * @param  int     $status
 * @param  array   $headers
 * @return \Nur\Http\Response
 */
if (! function_exists('response')) {
    function response($content = '', $status = 200, array $headers = [])
    {
        return;
    }
}

/**
 * Resolve a service from the container.
 *
 * @param  string  $name
 * @return mixed
 */
if (! function_exists('resolve')) {
    function resolve(string $name)
    {
        return app($name);
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

/**
 * Escape HTML special characters in a string.
 * 
 * @param string $value
 * @param bool $doubleEncode
 * 
 * @return string
 */
if (! function_exists('e')) {
    function e(string $value, $doubleEncode = true)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', $doubleEncode);
    }
}
