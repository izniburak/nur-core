<?php
/**
* nur - a simple framework for PHP Developers
*
* @author   izni burak demirtaş (@izniburak) <izniburak@gmail.com>
* @web      <http://burakdemirtas.org>
* @url      <https://github.com/izniburak/nur>
* @license  The MIT License (MIT) - <http://opensource.org/licenses/MIT>
*/

### Blade::make function
if (!function_exists('blade')) {
    function blade($view = null, $data = [], $mergeData = [])
    {
        return Blade::make($view, $data, $mergeData);
    }
}

### Load::view function
if (!function_exists('view')) {
    function view($name, $data = null)
    {
        return Load::view($name, $data);
    }
}

### Load::library function
if (!function_exists('library')) {
    function library($name, $params = null)
    {
        return Load::library($name, $params);
    }
}

### Load::model function
if (!function_exists('model')) {
    function model($file)
    {
        return Load::model($file);
    }
}

### Load::helper function
if (!function_exists('helper')) {
    function helper($name)
    {
        return Load::helper($name);
    }
}

### Error::message function
if (!function_exists('error')) {
    function error($title = null, $msg = null, $page = null)
    {
        return Load::error($title, $msg, $page);
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
        if(Sess::hasKey('_nur_token')) {
            Sess::delete('_nur_token');
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
        Session::set('_nur_csrf_token' . (!is_null($name) ? '_' . $name : ''), $csrf);
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
        $name = (!is_null($name) ? '_' . $name : '');
        if (Session::hasKey('_nur_csrf_token' . $name) && $token === Session::get('_nur_csrf_token' . $name)) {
            Session::delete('_nur_csrf_token' . $name);
            return true;
        }
        return false;
    }
}

### get config values function
if (!function_exists('config')) {
    function config($param = null)
    {   
        global $app;
        $config = $app->config();
        
        if(is_null($param))
            return $config;

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

### dd function
if (!function_exists('dd')) {
    function dd($str)
    {
        die(var_dump($str));
    }
}

### dump function
if (!function_exists('dump')) {
    function dump($str)
    {
        var_dump($str);
    }
}
