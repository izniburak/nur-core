<?php
/**
 * nur - a simple framework for PHP Developers
 *
 * @author   izni burak demirtaş (@izniburak) <izniburak@gmail.com>
 * @web      <http://burakdemirtas.org>
 * @url      <https://github.com/izniburak/nur>
 * @license  The MIT License (MIT) - <http://opensource.org/licenses/MIT>
 */

use Nur\Container\Container as Container;

if (! function_exists('app')) {
    /**
     * Get the available container instance.
     *
     * @param string $abstract
     * @param array  $parameters
     *
     * @return mixed|\Nur\Container\Container
     * @throws
     */
    function app($abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return Container::getInstance();
        }
        return Container::getInstance()->make($abstract, $parameters);
    }
}

if (! function_exists('config')) {
    /**
     * Get/set config values
     *
     * @param string|null $key
     * @param string|null $default
     *
     * @return mixed
     * @throws
     */
    function config($key = null, $default = null)
    {
        /**
         * @var $config \Nur\Config\Config
         */
        $config = app('config');
        if (func_num_args() === 0) {
            return $config;
        }

        if (is_array($key)) {
            return $config->set($key);
        }

        return $config->get($key, $default);
    }
}

if (! function_exists('abort')) {
    /**
     * Throw an HttpException with the given data.
     *
     * @param int    $code
     * @param string $message
     * @param array  $headers
     *
     * @return void
     * @throws
     */
    function abort($code, $message = null, array $headers = [])
    {
        if ($code === 404) {
            throw new \Nur\Exception\NotFoundHttpException($message);
        }

        throw new \Nur\Exception\HttpException($code, $message, null, $headers);
    }
}

if (! function_exists('abort_if')) {
    /**
     * Throw an HttpException with the given data if the given condition is true.
     *
     * @param bool   $boolean
     * @param int    $code
     * @param string $message
     * @param array  $headers
     *
     * @return void
     * @throws
     */
    function abort_if($boolean, $code, $message = null, array $headers = [])
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
     * @return \Nur\Http\Response|string
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
     * @return \Nur\Http\Response|string
     */
    function view($name, array $data = [])
    {
        return response()->view($name, $data);
    }
}

if (! function_exists('helper')) {
    /**
     * Load a helper
     *
     * @param string|null $file
     * @param string|null $directory
     *
     * @return mixed
     */
    function helper($file, $directory = null)
    {
        return app('load')->helper($file, $directory ?? 'Helpers');
    }
}

if (! function_exists('auth')) {
    /**
     * Authentication
     *
     * @param null|\Nur\Database\Model $user
     *
     * @return bool|Nur\Auth\Auth
     */
    function auth($user = null)
    {
        $auth = app(\Nur\Auth\Auth::class);
        if (is_null($user)) {
            return $auth;
        }

        return app($auth)->login($user);
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

if (! function_exists('redirect')) {
    /**
     * Redirect URL
     *
     * @param string|null $url
     * @param int         $statusCode
     * @param bool        $secure
     *
     * @return void|null
     */
    function redirect($url = null, $statusCode = 301, $secure = false)
    {
        return uri()->redirect($url, $statusCode, $secure);
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
        return app('listener')->trigger($event, $params, $method);
    }
}

if (! function_exists('encrypt')) {
    /**
     * Encrypt the given value.
     *
     * @param mixed $value
     * @param bool  $serialize
     *
     * @return string
     */
    function encrypt($value, $serialize = true)
    {
        return app('encrypter')->encrypt($value, $serialize);
    }
}

if (! function_exists('decrypt')) {
    /**
     * Decrypt the given value.
     *
     * @param string $value
     * @param bool   $unserialize
     *
     * @return mixed
     */
    function decrypt($value, $unserialize = true)
    {
        return app('encrypter')->decrypt($value, $unserialize);
    }
}

if (! function_exists('hasher')) {
    /**
     * Hash the given value against the selected algorithm in Hash config.
     *
     * @param string $value
     * @param array  $options
     *
     * @return string|\Nur\Hash\Hash
     * @throws
     */
    function hasher($value = null, $options = [])
    {
        if (is_null($value)) {
            return app('hash');
        }

        return app('hash')->make($value, $options);
    }
}

if (! function_exists('bcrypt')) {
    /**
     * Hash the given value against the bcrypt algorithm.
     *
     * @param string $value
     * @param array  $options
     *
     * @return string|\Nur\Hash\BcryptHash
     */
    function bcrypt($value = null, $options = [])
    {
        if (is_null($value)) {
            return hasher()->createBcryptDriver();
        }

        return hasher()->createBcryptDriver()->make($value, $options);
    }
}

if (! function_exists('app_path')) {
    /**
     * Get the app path.
     *
     * @param string $path
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
     * @param string $path
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
     * @param string $path
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
     * @param string $path
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
     * @param string $path
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
     * @param string $path
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
     * @param string $path
     *
     * @return string
     */
    function public_path($path = '')
    {
        return app('path.public') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
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
        session()->set('_nur_csrf' . (! is_null($name) ? '_' . $name : ''), $csrf);

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
        $name = (! is_null($name) ? '_' . $name : '');
        if (session()->has('_nur_csrf' . $name) &&
            is_string($token) &&
            $token === session()->get('_nur_csrf' . $name)) {
            session()->delete('_nur_csrf' . $name);
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
     * @param array|string $key
     * @param mixed        $default
     *
     * @return \Nur\Http\Request|string|array
     */
    function request($key = null, $default = null)
    {
        $request = app(\Nur\Http\Request::class);
        if (is_null($key)) {
            return $request;
        }
        if (is_array($key)) {
            return $request->only($key);
        }
        $value = $request->__get($key);
        return is_null($value) ? value($default) : $value;
    }
}

if (! function_exists('response')) {
    /**
     * Return a new response from the application.
     *
     * @param string|array|null $content
     * @param int               $status
     * @param array             $headers
     *
     * @return \Nur\Http\Response|string
     */
    function response($content = '', $status = 200, array $headers = [])
    {
        $response = app(\Nur\Http\Response::class);
        if (func_num_args() === 0) {
            return $response;
        }

        return $response->create($content, $status, $headers);
    }
}

if (! function_exists('resolve')) {
    /**
     * Resolve a service from the container.
     *
     * @param string $name
     * @param array  $parameters
     *
     * @return mixed
     */
    function resolve(string $name, array $parameters = [])
    {
        return app($name, $parameters);
    }
}

if (! function_exists('trans')) {
    /**
     * Translate the given message.
     *
     * @param  string|null  $key
     * @param  array  $replace
     * @param  string|null  $locale
     * @return \Nur\Translation\Translator|string|array|null
     */
    function trans($key = null, $replace = [], $locale = null)
    {
        if (is_null($key)) {
            return app('translator');
        }
        return app('translator')->get($key, $replace, $locale);
    }
}

if (! function_exists('trans_choice')) {
    /**
     * Translates the given message based on a count.
     *
     * @param  string  $key
     * @param  \Countable|int|array  $number
     * @param  array  $replace
     * @param  string|null  $locale
     * @return string
     */
    function trans_choice($key, $number, array $replace = [], $locale = null)
    {
        return app('translator')->choice($key, $number, $replace, $locale);
    }
}

if (! function_exists('__')) {
    /**
     * Translate the given message.
     *
     * @param  string|null  $key
     * @param  array  $replace
     * @param  string|null  $locale
     * @return string|array|null
     */
    function __($key = null, $replace = [], $locale = null)
    {
        if (is_null($key)) {
            return $key;
        }
        return trans($key, $replace, $locale);
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
    function e(string $value, $doubleEncode = true): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', $doubleEncode);
    }
}

if (! function_exists('paginationLinks')) {
    /**
     * Pagination links for records.
     *
     * @param $records
     * @param $link
     * @param array $settings
     * @param bool $simple
     *
     * @return string|void
     */
    function paginationLinks($records, $link = '', array $settings = [], $simple = false)
    {
        if (is_null($records) || empty($records)) {
            return;
        }

        $config = [
            'class' => 'pagination pagination-sm',
            'scroll' => 5,
            'show' => 10,
            'next' => '&raquo;',
            'prev' => '&laquo;',
        ];
        $config = array_merge($config, $settings);

        $ul = '<ul class="' . $config['class'] . '">';
        $scroll = $config['scroll'];
        $show = $config['show'];

        $link = $link === '' ? uri()->current() : $link;
        $info = $records->toArray();
        $pageName = 'page';
        $total = $info['last_page'];
        $page = $info['current_page'];

        $link = preg_replace('/([?&])' . $pageName . '=[^&]+(&|$)/', '$1', $link);
        $link = trim($link, '&');
        $link = trim($link, '?');
        $link .= strstr($link, '?') ? '&' : '?';

        if ($total > 1) {
            $page = (intval($page) ? $page : 1);
            if ($page != 1) {
                $ul .= '<li><a href="' . ($link) . $pageName . '=' . ($page - 1) . '">' . $config['prev'] . '</a>';
            }

            if (! $simple) {
                if ($total <= $scroll) {
                    if ($total <= $show) {
                        $start = 1;
                        $finish = $total;
                    } else {
                        $start = 1;
                        $finish = $total;
                    }
                } else {
                    if ($page < intval($scroll / 2) + 1) {
                        $start = 1;
                        $finish = $scroll;
                    } else {
                        $start = $page - intval($scroll / 2);
                        $finish = $page + intval($scroll / 2);
                        if ($finish > $total) {
                            $finish = $total;
                        }
                    }
                }

                for ($i = $start; $i <= $finish; $i++) {
                    if ($page == $i) {
                        $ul .= '<li class="active"><a href="javascript:;">' . $i . '</a></li>';
                    } else {
                        $ul .= '<li><a href="' . ($link) . $pageName . '=' . ($i) . '">' . $i . '</a></li>';
                    }
                }
            }

            if ($page != $total) {
                $ul .= '<li><a href="' . ($link) . $pageName . '=' . ($page + 1) . '">' . $config['next'] . '</a>';
            }
        } else {
            return '';
        }

        $ul .= '</ul>';
        $ul = str_replace('//', '/', $ul);
        $ul = str_replace(['http:/', 'https:/'], ['http://', 'https://'], $ul);

        return $ul;
    }
}

if (! function_exists('simplePaginationLinks')) {
    function simplePaginationLinks($records, $link = '', array $settings = [])
    {
        return paginationLinks($records, $link, $settings, true);
    }
}
