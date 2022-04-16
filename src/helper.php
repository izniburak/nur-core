<?php
/**
 *
 * Some of these helpers adapted from helpers of Laravel Framework
 * You can check out those helpers.
 *
 * @see https://github.com/laravel/framework/blob/8.x/src/Illuminate/Support/helpers.php
 * @see https://github.com/laravel/framework/blob/8.x/src/Illuminate/Foundation/helpers.php
 */

use Nur\Container\Container as Container;

if (!function_exists('app')) {
    /**
     * Get the available container instance.
     *
     * @param string|null $abstract
     * @param array       $parameters
     *
     * @return mixed|\Nur\Kernel\Application
     * @throws
     */
    function app(?string $abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return Container::getInstance();
        }

        return Container::getInstance()->make($abstract, $parameters);
    }
}

if (!function_exists('config')) {
    /**
     * Get/set config values
     *
     * @param string|null $key
     * @param string|null $default
     *
     * @return mixed|void
     * @throws
     */
    function config(?string $key = null, $default = null)
    {
        /** @var $config \Nur\Config\Config */
        $config = app('config');
        if (func_num_args() === 0) {
            return $config;
        }

        if (is_array($key)) {
            $config->set($key);
            return;
        }

        return $config->get($key, $default);
    }
}

if (!function_exists('abort')) {
    /**
     * Throw an HttpException with the given data.
     *
     * @param int         $code
     * @param string|null $message
     * @param array       $headers
     *
     * @return void
     */
    function abort(int $code, string $message = null, array $headers = [])
    {
        if ($code === 404) {
            throw new \Nur\Exception\NotFoundHttpException($message);
        }

        throw new \Nur\Exception\HttpException($code, $message, null, $headers);
    }
}

if (!function_exists('abort_if')) {
    /**
     * Throw an HttpException with the given data if the given condition is true.
     *
     * @param bool        $boolean
     * @param int         $code
     * @param string|null $message
     * @param array       $headers
     *
     * @return void
     */
    function abort_if(bool $boolean, int $code, string $message = null, array $headers = [])
    {
        if ($boolean) {
            abort($code, $message, $headers);
        }
    }
}

if (!function_exists('logger')) {
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

if (!function_exists('blade')) {
    /**
     * Blade template engine
     *
     * @param string $view
     * @param array  $data
     * @param array  $mergeData
     *
     * @return \Nur\Http\Response|string
     * @throws
     */
    function blade(string $view, array $data = [], array $mergeData = [])
    {
        return response()->blade($view, $data, $mergeData);
    }
}

if (!function_exists('view')) {
    /**
     * View template
     *
     * @param string $name
     * @param array  $data
     *
     * @return \Nur\Http\Response|string
     */
    function view(string $name, array $data = [])
    {
        return response()->view($name, $data);
    }
}

if (!function_exists('helper')) {
    /**
     * Load a helper
     *
     * @param string      $file
     * @param string|null $directory
     *
     * @return mixed
     */
    function helper(string $file, ?string $directory = null)
    {
        return app('load')->helper($file, $directory ?? 'Helpers');
    }
}

if (!function_exists('auth')) {
    /**
     * Authentication
     *
     * @return Nur\Auth\Auth
     */
    function auth(): Nur\Auth\Auth
    {
        /** @var \Nur\Auth\Auth $auth */
        return app(\Nur\Auth\Auth::class);
    }
}

if (!function_exists('admin')) {
    /**
     * Authentication for Admin Panel Users
     *
     * @return \Nur\Auth\Auth
     * @throws
     */
    function admin(): \Nur\Auth\Auth
    {
        return app()->makeWith(\Nur\Auth\Auth::class, ['prefix' => 'admin']);
    }
}

if (!function_exists('session')) {
    /**
     * Sessions
     *
     * @param string|null $name
     *
     * @return Nur\Http\Session|string|array|int
     */
    function session(?string $name = null)
    {
        /** @var \Nur\Http\Session $session */
        $session = app(\Nur\Http\Session::class);
        if (is_null($name)) {
            return $session;
        }

        return $session->get($name);
    }
}

if (!function_exists('cookie')) {
    /**
     * Cookies
     *
     * @param string|null $name
     *
     * @return Nur\Http\Cookie|string|int
     */
    function cookie(?string $name = null)
    {
        /** @var \Nur\Http\Cookie $cookie */
        $cookie = app(\Nur\Http\Cookie::class);
        if (is_null($name)) {
            return $cookie;
        }

        return $cookie->get($name);
    }
}

if (!function_exists('uri')) {
    /**
     * Uri class
     *
     * @param string|null $name
     *
     * @return string|Nur\Uri\Uri
     */
    function uri(?string $name = null)
    {
        if (is_null($name)) {
            return app('uri');
        }

        return app('uri')->base($name);
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirect URL
     *
     * @param string|null $url
     * @param int         $statusCode
     * @param bool        $secure
     *
     * @return void|null
     */
    function redirect(?string $url = null, int $statusCode = 301, bool $secure = false)
    {
        uri()->redirect($url, $statusCode, $secure);
    }
}

if (!function_exists('event')) {
    /**
     * Event trigger for Events.
     *
     * @param string $event
     * @param array  $params
     * @param string $method
     *
     * @return mixed
     */
    function event(string $event, array $params = [], string $method = 'handle')
    {
        return app(\Nur\Event\Event::class)->trigger($event, $params, $method);
    }
}

if (!function_exists('encrypt')) {
    /**
     * Encrypt the given value.
     *
     * @param mixed $value
     * @param bool  $serialize
     *
     * @return string
     */
    function encrypt($value, bool $serialize = true)
    {
        return app('encrypter')->encrypt($value, $serialize);
    }
}

if (!function_exists('decrypt')) {
    /**
     * Decrypt the given value.
     *
     * @param string $value
     * @param bool   $unserialize
     *
     * @return mixed
     */
    function decrypt($value, bool $unserialize = true)
    {
        return app('encrypter')->decrypt($value, $unserialize);
    }
}

if (!function_exists('hasher')) {
    /**
     * Hash the given value against the selected algorithm in Hash config.
     *
     * @param string|null $value
     * @param array       $options
     *
     * @return string|\Nur\Hash\Hash
     * @throws
     */
    function hasher(string $value = null, array $options = [])
    {
        if (is_null($value)) {
            return app('hash');
        }

        return app('hash')->make($value, $options);
    }
}

if (!function_exists('bcrypt')) {
    /**
     * Hash the given value against the bcrypt algorithm.
     *
     * @param string|null $value
     * @param array       $options
     *
     * @return string|\Nur\Hash\BcryptHash
     */
    function bcrypt(string $value = null, array $options = [])
    {
        if (is_null($value)) {
            return hasher()->createBcryptDriver();
        }

        return hasher()->createBcryptDriver()->make($value, $options);
    }
}

if (!function_exists('app_path')) {
    /**
     * Get the app path.
     *
     * @param string $path
     *
     * @return string
     */
    function app_path(string $path = ''): string
    {
        return app()->path($path);
    }
}

if (!function_exists('base_path')) {
    /**
     * Get the base path.
     *
     * @param string $path
     *
     * @return string
     */
    function base_path(string $path = ''): string
    {
        return app()->basePath($path);
    }
}

if (!function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param string $path
     *
     * @return string
     */
    function config_path(string $path = ''): string
    {
        return app()->configPath($path);
    }
}

if (!function_exists('storage_path')) {
    /**
     * Get the storage path.
     *
     * @param string $path
     *
     * @return string
     */
    function storage_path(string $path = ''): string
    {
        return app()->storagePath($path);
    }
}

if (!function_exists('database_path')) {
    /**
     * Get the database path.
     *
     * @param string $path
     *
     * @return string
     */
    function database_path(string $path = ''): string
    {
        return app()->databasePath($path);
    }
}

if (!function_exists('cache_path')) {
    /**
     * Get the cache path.
     *
     * @param string $path
     *
     * @return string
     */
    function cache_path(string $path = ''): string
    {
        return app()->cachePath($path);
    }
}

if (!function_exists('public_path')) {
    /**
     * Get the public path.
     *
     * @param string $path
     *
     * @return string
     */
    function public_path(string $path = ''): string
    {
        return app()->publicPath($path);
    }
}

if (!function_exists('csrf_token')) {
    /**
     * CSRF Token Generate
     *
     * @param string|null $name
     *
     * @return string
     * @throws
     */
    function csrf_token(?string $name = null): string
    {
        $csrf = hash_hmac('sha256', config('app.key'), uniqid('', true));
        session()->set('_nur_csrf' . (!is_null($name) ? '_' . $name : ''), $csrf);

        return $csrf;
    }
}

if (!function_exists('csrf_check')) {
    /**
     * CSRF Token Check
     *
     * @param string      $token
     * @param string|null $name
     *
     * @return bool
     */
    function csrf_check(string $token, $name = null): bool
    {
        $name = (!is_null($name) ? '_' . $name : '');
        if (session()->has('_nur_csrf' . $name) &&
            $token === session()->get('_nur_csrf' . $name)) {
            session()->delete('_nur_csrf' . $name);
            return true;
        }

        return false;
    }
}

if (!function_exists('csrf_field')) {
    /**
     * CSRF Token Html Field
     *
     * @param string|null $name
     *
     * @return string
     */
    function csrf_field(?string $name = null): string
    {
        return '<input type="hidden" name="_token" value="' . csrf_token($name) . '" />'
            . ($name ? '<input type="hidden" name="_token_form" value="' . $name . '" />' : '');
    }
}

if (!function_exists('method_field')) {
    /**
     * Generate a form field to spoof the HTTP verb used by forms.
     *
     * @param string $method
     *
     * @return string
     */
    function method_field(string $method): string
    {
        return '<input type="hidden" name="_method" value="' . $method . '" />';
    }
}

if (!function_exists('request')) {
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

if (!function_exists('response')) {
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
        if (func_num_args() === 0) {
            return app(\Nur\Http\Response::class);
        }

        return app()->make(\Nur\Http\Response::class, [
            'content' => $content,
            'status' => $status,
            'headers' => $headers,
        ]);
    }
}

if (!function_exists('validation')) {
    /**
     * Validation service from the container.
     *
     * @return \Nur\Http\Validation
     */
    function validation()
    {
        return app(\Nur\Http\Validation::class);
    }
}

if (!function_exists('resolve')) {
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

if (!function_exists('now')) {
    /**
     * Create a new Carbon instance for the current time.
     *
     * @param \DateTimeZone|string|null $tz
     *
     * @return \Illuminate\Support\Carbon
     */
    function now($tz = null)
    {
        return app('date')->now($tz);
    }
}

if (!function_exists('files')) {
    /**
     * Get Filesystem instance from Container
     *
     * @return \Illuminate\Filesystem\Filesystem
     */
    function files()
    {
        return app('files');
    }
}

if (!function_exists('trans')) {
    /**
     * Translate the given message.
     *
     * @param string|null $key
     * @param array       $replace
     * @param string|null $locale
     *
     * @return \Nur\Translation\Translator|string|array|null
     */
    function trans(?string $key = null, $replace = [], $locale = null)
    {
        /** @var \Nur\Translation\Translator $translator */
        $translator = app('translator');
        if (is_null($key)) {
            return $translator;
        }

        return $translator->get($key, $replace, $locale);
    }
}

if (!function_exists('trans_choice')) {
    /**
     * Translates the given message based on a count.
     *
     * @param string               $key
     * @param \Countable|int|array $number
     * @param array                $replace
     * @param string|null          $locale
     *
     * @return string
     */
    function trans_choice(string $key, $number, array $replace = [], $locale = null)
    {
        return trans()->choice($key, $number, $replace, $locale);
    }
}

if (!function_exists('captcha')) {
    /**
     * Generate captcha code
     *
     * @param int $max
     *
     * @return object
     */
    function captcha(int $max = 10): object
    {
        $op = rand(0, 1);
        $n1 = rand(($max / 2), $max);
        $n2 = rand(1, ($max / 2));
        $result = $op === 1 ? $n1 - $n2 : $n1 + $n2;
        session()->set('captcha', $result);
        return (object)[
            'question' => $n1 . ($op === 1 ? ' - ' : ' + ') . $n2,
            'answer' => $result,
            'answer_encoded' => base64_encode($result),
        ];
    }
}

if (!function_exists('__')) {
    /**
     * Translate the given message.
     *
     * @param string|null $key
     * @param array       $replace
     * @param string|null $locale
     *
     * @return string|array|null
     */
    function __(?string $key = null, array $replace = [], string $locale = null)
    {
        if (is_null($key)) {
            return $key;
        }

        return trans($key, $replace, $locale);
    }
}

if (!function_exists('dd')) {
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

if (!function_exists('e')) {
    /**
     * Escape HTML special characters in a string.
     *
     * @param string $value
     * @param bool   $doubleEncode
     *
     * @return string
     */
    function e(string $value, bool $doubleEncode = true): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', $doubleEncode);
    }
}

if (!function_exists('paginationLinks')) {
    /**
     * Pagination links for records.
     *
     * @param       $records
     * @param string $link
     * @param array $settings
     * @param bool  $simple
     *
     * @return string|void
     */
    function paginationLinks($records, string $link = '', array $settings = [], bool $simple = false)
    {
        if (empty($records)) {
            return;
        }

        $config = [
            'class' => 'pagination justify-content-end',
            'active' => 'active',
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
                $ul .= '<li class="page-item">
                    <a class="page-link" href="' . ($link) . $pageName . '=' . ($page - 1) . '">' . $config['prev'] . '</a>';
            }

            if (!$simple) {
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
                        $ul .= '<li class="page-item ' . $config['active'] . '"><a class="page-link" href="javascript:;">' . $i . '</a></li>';
                    } else {
                        $ul .= '<li class="page-item"><a class="page-link" href="' . ($link) . $pageName . '=' . ($i) . '">' . $i . '</a></li>';
                    }
                }
            }

            if ($page != $total) {
                $ul .= '<li class="page-item"><a class="page-link" href="' . ($link) . $pageName . '=' . ($page + 1) . '">' . $config['next'] . '</a>';
            }
        } else {
            return '';
        }

        $ul .= '</ul>';
        $ul = str_replace('//', '/', $ul);
        return str_replace(['http:/', 'https:/'], ['http://', 'https://'], $ul);
    }
}

if (!function_exists('simplePaginationLinks')) {
    function simplePaginationLinks($records, $link = '', array $settings = [])
    {
        return paginationLinks($records, $link, $settings, true);
    }
}
