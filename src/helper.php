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

if (! function_exists('app')) {
    /**
     * Get application container or a service.
     *
     * @param string|null $name
     *
     * @return mixed|Nur\Container\Container
     */
    function app($name = null)
    {
        if (is_null($name)) {
            return Container::getInstance();
        }

        return Container::getInstance()->get($name);
    }
}

if (! function_exists('config')) {
    /**
     * Get config values
     *
     * @param string|null $param
     *
     * @return mixed
     */
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

if (! function_exists('abort')) {
    /**
     * Throw an HttpException with the given data.
     *
     * @param  int    $code
     * @param  string $message
     * @param  array  $headers
     *
     * @return void
     */
    function abort($code, $message = '', array $headers = [])
    {

    }
}

if (! function_exists('abort_if')) {
    /**
     * Throw an HttpException with the given data if the given condition is true.
     *
     * @param  bool   $boolean
     * @param  int    $code
     * @param  string $message
     * @param  array  $headers
     *
     * @return void
     */
    function abort_if($boolean, $code, $message = '', array $headers = [])
    {
        if ($boolean) {
            abort($code, $message, $headers);
        }
    }
}

if (! function_exists('logger')) {
    /**
     * Logger
     *
     * @param mixed|null $message
     *
     * @return mixed|Nur\Log\Log
     */
    function logger($message = null)
    {
        if (is_null($message)) {
            return app('log');
        }

        return app('log')->debug($message);
    }
}

if (! function_exists('blade')) {
    /**
     * Blade template engine
     *
     * @param string $view
     * @param array  $data
     * @param array  $mergeData
     *
     * @return string
     */
    function blade($view, array $data = [], array $mergeData = [])
    {
        return response()->blade($view, $data, $mergeData);
    }
}

if (! function_exists('view')) {
    /**
     * View template
     *
     * @param string $name
     * @param array  $data
     *
     * @return string
     */
    function view($name, array $data = [])
    {
        return response()->view($name, $data);
    }
}

if (! function_exists('error')) {
    /**
     * Error messages as view
     *
     * @param string|null $title
     * @param string|null $message
     * @param string|null $page
     *
     * @return string
     */
    function error($title = null, $message = null, $page = null)
    {
        return app('load')->error($title, $message, $page);
    }
}

if (! function_exists('session')) {
    /**
     * Sessions
     *
     * @param string|null $name
     *
     * @return mixed|Nur\Http\Session
     */
    function session($name = null)
    {
        if (is_null($name)) {
            return app('session');
        }

        return app('session')->get($name);
    }
}

if (! function_exists('cookie')) {
    /**
     * Cookies
     *
     * @param string|null $name
     *
     * @return mixed|Nur\Http\Cookie
     */
    function cookie($name = null)
    {
        if (is_null($name)) {
            return app('cookie');
        }

        return app('cookie')->get($name);
    }
}

if (! function_exists('uri')) {
    /**
     * Uri class
     *
     * @param string|null $name
     *
     * @return string|Nur\Uri\Uri
     */
    function uri($name = null)
    {
        if (is_null($name)) {
            return app('uri');
        }

        return app('uri')->base($name);
    }
}

if (! function_exists('http')) {
    /**
     * Http methods
     *
     * @param string|null $name
     *
     * @return mixed|Nur\Http\Http
     */
    function http($name = null)
    {
        if (is_null($name)) {
            return app('http');
        }

        return app('http')->request($name);
    }
}

if (! function_exists('event')) {
    /**
     * Event trigger for Listeners.
     *
     * @param string $event
     * @param array  $params
     * @param string $method
     *
     * @return mixed
     */
    function event($event, array $params = [], $method = 'handle')
    {
        return app('event')->trigger($event, $params, $method);
    }
}

if (! function_exists('app_path')) {
    /**
     * Get the app path.
     *
     * @param  string $path
     *
     * @return string
     */
    function app_path($path = '')
    {
        return app('path') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (! function_exists('base_path')) {
    /**
     * Get the base path.
     *
     * @param  string $path
     *
     * @return string
     */
    function base_path($path = '')
    {
        return app('path.base') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (! function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param  string $path
     *
     * @return string
     */
    function config_path($path = '')
    {
        return app('path.config') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (! function_exists('storage_path')) {
    /**
     * Get the storage path.
     *
     * @param  string $path
     *
     * @return string
     */
    function storage_path($path = '')
    {
        return app('path.storage') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (! function_exists('database_path')) {
    /**
     * Get the database path.
     *
     * @param  string $path
     *
     * @return string
     */
    function database_path($path = '')
    {
        return app('path.database') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (! function_exists('cache_path')) {
    /**
     * Get the cache path.
     *
     * @param  string $path
     *
     * @return string
     */
    function cache_path($path = '')
    {
        return app('path.cache') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (! function_exists('public_path')) {
    /**
     * Get the public path.
     *
     * @param  string $path
     *
     * @return string
     */
    function public_path($path = '')
    {
        return app('path.public') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (! function_exists('get_token')) {
    /**
     * Get Application token
     *
     * @return string
     */
    function get_token()
    {
        return _TOKEN;
    }
}

if (! function_exists('reset_token')) {
    /**
     * Application token reset
     *
     * @return void
     */
    function reset_token()
    {
        if (session()->hasKey('_nur_token')) {
            session()->delete('_nur_token');
        }

        return;
    }
}

if (! function_exists('csrf_token')) {
    /**
     * CSRF Token Generate
     *
     * @param string|null $name
     *
     * @return string
     * @throws
     */
    function csrf_token($name = null)
    {
        $csrf = hash_hmac('sha256', get_token(), uniqid('', true));
        session()->set('_nur_csrf_token' . (! is_null($name) ? '_' . $name : ''), $csrf);

        return $csrf;
    }
}

if (! function_exists('csrf_check')) {
    /**
     * CSRF Token Check
     *
     * @param string      $token
     * @param string|null $name
     *
     * @return bool
     */
    function csrf_check($token, $name = null)
    {
        $session = session();
        $name = (! is_null($name) ? '_' . $name : '');
        if ($session->hasKey('_nur_csrf_token' . $name) && $token === $session->get('_nur_csrf_token' . $name)) {
            $session->delete('_nur_csrf_token' . $name);
            return true;
        }

        return false;
    }
}

if (! function_exists('csrf_field')) {
    /**
     * CSRF Token Html Field
     *
     * @param string|null $name
     *
     * @return string
     */
    function csrf_field(string $name = null)
    {
        return '<input type="hidden" name="_token" value="' . csrf_token($name) . '" />';
    }
}

if (! function_exists('method_field')) {
    /**
     * Generate a form field to spoof the HTTP verb used by forms.
     *
     * @param string $method
     *
     * @return string
     */
    function method_field(string $method)
    {
        return '<input type="hidden" name="_method" value="' . $method . '" />';
    }
}

if (! function_exists('request')) {
    /**
     * Get an instance of the current request or an input item from the request.
     *
     * @param  array|string $key
     * @param  mixed        $default
     *
     * @return \Nur\Http\Request|string|array
     */
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

if (! function_exists('response')) {
    /**
     * Return a new response from the application.
     *
     * @param  string|array|null $content
     * @param  int               $status
     * @param  array             $headers
     *
     * @return \Nur\Http\Response
     */
    function response($content = '', $status = 200, array $headers = [])
    {
        if (func_num_args() === 0) {
            return app('response');
        }

        return app('response')->create($content, $status, $headers);
    }
}

if (! function_exists('resolve')) {
    /**
     * Resolve a service from the container.
     *
     * @param  string $name
     *
     * @return mixed
     */
    function resolve(string $name)
    {
        return app($name);
    }
}

if (! function_exists('dd')) {
    /**
     * Dump and Die function
     *
     * @param $args
     *
     * @return string
     */
    function dd(...$args)
    {
        foreach ($args as $arg) {
            var_dump($arg);
        }

        die(1);
    }
}

if (! function_exists('e')) {
    /**
     * Escape HTML special characters in a string.
     *
     * @param string $value
     * @param bool   $doubleEncode
     *
     * @return string
     */
    function e(string $value, $doubleEncode = true)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', $doubleEncode);
    }
}
