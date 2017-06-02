<?php
/**
* nur - a simple framework for PHP Developers
*
* @author   izni burak demirtaş (@izniburak) <izniburak@gmail.com>
* @web      <http://burakdemirtas.org>
* @url      <https://github.com/izniburak/nur>
* @license  The MIT License (MIT) - <http://opensource.org/licenses/MIT>
*/

use Nur\Load\Load;
use Nur\Error\Error;
use Nur\Blade\Blade;
use Nur\Http\Session as Sess;

### Load::view function
if (!function_exists('view'))
{
    function view($name, $data = null)
    {
        return Load::getInstance()->view($name, $data);
    }
}

### Blade::make function
if (!function_exists('blade'))
{
    function blade($view = null, $data = [], $mergeData = [])
    {
        return blade::make($view, $data, $mergeData);
    }
}

### Load::library function
if (!function_exists('library'))
{
    function library($name, $params = null)
    {
        return Load::getInstance()->library($name, $params);
    }
}

### Load::model function
if (!function_exists('model'))
{
    function model($file)
    {
        return Load::getInstance()->model($file);
    }
}

### Load::helper function
if (!function_exists('helper'))
{
    function helper($name)
    {
        return Load::getInstance()->helper($name);
    }
}

### Error::message function
if (!function_exists('error'))
{
    function error($title = null, $msg = null, $page = null)
    {
        return Error::message($title, $msg, $page);
    }
}

### token generator function
if (!function_exists('getToken'))
{
    function getToken()
    {
        return _TOKEN;
    }
}

### token reset function
if (!function_exists('resetToken'))
{
    function resetToken()
    {
        if(isset($_SESSION['_token']))
        {
            $_SESSION['_token'] = '';
            unset($_SESSION['_token']);
        }
    }
}

/**
* CSRF Token Generate
* @return string
*/
if (!function_exists('csrfToken'))
{
    function csrfToken($name = null)
    {
        $csrf = hash_hmac('sha256', getToken(), uniqid('', true));
        Sess::set('_nur_csrf_token' . (!is_null($name) ? '_' . $name : ''), $csrf);
        return $csrf;
    }
}

/**
* CSRF Token Check
* @param $token
* @return boolean
*/
if (!function_exists('csrfCheck'))
{
    function csrfCheck($token, $name = null)
    {
        $name = (!is_null($name) ? '_' . $name : '');
        if (Sess::hasKey('_nur_csrf_token' . $name) && $token === Sess::get('_nur_csrf_token' . $name))
        {
            Sess::delete('_nur_csrf_token' . $name);
            return true;
        }
        return false;
    }
}

### get config values function
if (!function_exists('getConfig'))
{
    function getConfig()
    {
        global $config;
        return $config;
    }
}

if (!class_exists('Uri'))
{
    class Uri extends Nur\Uri\Uri { }
}

if (!class_exists('Http'))
{
    class Http extends Nur\Http\Http { }
}

if (!class_exists('Session'))
{
    class Session extends Nur\Http\Session { }
}

if (!class_exists('Cookie'))
{
    class Cookie extends Nur\Http\Cookie { }
}
