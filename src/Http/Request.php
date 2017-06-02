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

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Request
{
    /**
    * Class instance variable
    */
    private static $instance = null;

    public static $request = null;
    public static $query = null;
    public static $cookies = null;
    public static $attributes = null;
    public static $files = null;
    public static $server = null;
    public static $headers = null;

    /**
    * Call static function for Request Class
    *
    * @return mixed
    */
    public static function __callStatic($method, $parameters)
    {
        return call_user_func_array([self::getInstance(), $method], $parameters);
    }

    /**
    * Get class instance
    *
    * @return SymfonyRequestObject
    */
    public static function getInstance()
    {
        if (null === self::$instance)
        {
            self::$instance = SymfonyRequest::createFromGlobals();

            self::$request = self::$instance->request;
            self::$query = self::$instance->query;
            self::$cookies = self::$instance->cookies;
            self::$attributes = self::$instance->attributes;
            self::$files = self::$instance->files;
            self::$server = self::$instance->server;
            self::$headers = self::$instance->headers;
        }

        return self::$instance;
    }
}
