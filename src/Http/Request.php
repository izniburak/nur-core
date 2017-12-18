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

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Request extends SymfonyRequest 
{
    /**
     * Class constructer.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct($_GET, $_POST, array(), $_COOKIE, $_FILES, $_SERVER);
    }

    /**
     * Get the header values.
     *
     * @return HeaderBag
     */
    public function headers() 
    {
        return $this->headers;
    }

    /**
     * Get the $_POST values.
     *
     * @return ParameterBag
     */
    public function input() 
    {
        return $this->request;
    }

    /**
     * Get the $_GET values.
     *
     * @return ParameterBag
     */
    public function query() 
    {
        return $this->query;
    }
    
    /**
     * Get the $_FILES values.
     *
     * @return FileBag
     */
    public function files() 
    {
        return $this->files;
    }

    /**
     * Get the $_SERVER values.
     *
     * @return ServerBag
     */
    public function server() 
    {
        return $this->server;
    }

    /**
     * Get the attribute values.
     *
     * @return ParameterBag
     */
    public function attributes() 
    {
        return $this->attributes;
    }

    /**
     * Get the $_COOKIE values.
     *
     * @return ParameterBag
     */
    public function cookies() 
    {
        return $this->cookies;
    }

    /**
     * Get the request method.
     *
     * @return string
     */
    public function method()
    {
        return $this->getMethod();
    }

    /**
     * Get the root URL for the application.
     *
     * @return string
     */
    public function root()
    {
        return rtrim($this->getSchemeAndHttpHost().$this->getBaseUrl(), '/');
    }

    /**
     * Get the URL (no query string) for the request.
     *
     * @return string
     */
    public function url()
    {
        return rtrim(preg_replace('/\?.*/', '', $this->getUri()), '/');
    }

    /**
     * Get the full URL for the request.
     *
     * @return string
     */
    public function fullUrl()
    {
        $query = $this->getQueryString();
        $question = $this->getBaseUrl().$this->getPathInfo() == '/' ? '/?' : '?';
        return $query ? $this->url().$question.$query : $this->url();
    }

    /**
     * Get the full URL for the request with the added query string parameters.
     *
     * @param  array  $query
     * @return string
     */
    public function fullUrlWithQuery(array $query)
    {
        $question = $this->getBaseUrl().$this->getPathInfo() == '/' ? '/?' : '?';
        return count($this->query()) > 0
            ? $this->url().$question.http_build_query(array_merge($this->query(), $query))
            : $this->fullUrl().$question.http_build_query($query);
    }

    /**
     * Get the current path info for the request.
     *
     * @return string
     */
    public function path()
    {
        $pattern = trim($this->getPathInfo(), '/');
        return $pattern == '' ? '/' : $pattern;
    }

    /**
     * Determine if the request is the result of an AJAX call.
     *
     * @return bool
     */
    public function ajax()
    {
        return $this->isXmlHttpRequest();
    }
    /**
     * Determine if the request is the result of an PJAX call.
     *
     * @return bool
     */
    public function pjax()
    {
        return $this->headers->get('X-PJAX') == true;
    }

    /**
     * Determine if the request is over HTTPS.
     *
     * @return bool
     */
    public function secure()
    {
        return $this->isSecure();
    }

    /**
     * Get the client IP address.
     *
     * @return string
     */
    public function ip()
    {
        return $this->getClientIp();
    }

    /**
     * Get the client IP addresses.
     *
     * @return array
     */
    public function ips()
    {
        return $this->getClientIps();
    }

    /**
     * Get the client user agent.
     *
     * @return string
     */
    public function userAgent()
    {
        return $this->headers->get('User-Agent');
    }
}
