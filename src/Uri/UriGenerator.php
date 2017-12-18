<?php
/**
* nur - a simple framework for PHP Developers
*
* @author   izni burak demirtaş (@izniburak) <izniburak@gmail.com>
* @web      <http://burakdemirtas.org>
* @url      <https://github.com/izniburak/nur>
* @license  The MIT License (MIT) - <http://opensource.org/licenses/MIT>
*/

namespace Nur\Uri;

use Nur\Facades\Http;
use Nur\Facades\Route;

class UriGenerator
{
    protected $base = null;
    protected $url = null;
    protected $https = false;
    protected $cachedHttps = false;

    /**
    * Create URI class values.
    *
    * @return string | null
    */
    function __construct()
    {
        $this->base = BASE_FOLDER;

        $this->url = Http::server('HTTP_HOST') . '/' . $this->base . '/';
        if( (!empty(Http::server('HTTPS')) && Http::server('HTTPS') !== 'off') ||
            Http::server('SERVER_PORT') == 443 || config('https') === true)
            $this->cachedHttps = true;
    }

    /**
    * Get base url for app.
    *
    * @return string
    */
    public function base($data = null, $secure = false)
    {
        $data = (!is_null($data)) ? $this->url . $data : $this->url . '/';
        return $this->getUrl($data, $secure);
    }

    /**
    * Get admin url for app.
    *
    * @return string
    */
    public function admin($data = null, $secure = false)
    {
        $data = (!is_null($data)) ? $this->url . '/' . ADMIN_FOLDER . '/' . $data : $this->url . '/' . ADMIN_FOLDER . '/';
        return $this->getUrl($data, $secure);
    }

    /**
    * Get route uri value with params.
    *
    * @return string
    */
    public function route($name, $params = null, $secure = false)
    {
        $routes = Route::getRoutes();
        $found = false;

        foreach ($routes as $key => $value)
        {
            if($value['alias'] == $name)
            {
                $found = true;
                break;
            }
        }

        if($found)
        {
            if(strstr($routes[$key]['route'], '{'))
            {
                $segment = explode('/', $routes[$key]['route']);
                $i = 0;
                foreach ($segment as $key => $value)
                {
                    if(strstr($value, '{'))
                    {
                        $segment[$key] = $params[$i];
                        $i++;
                    }
                }
                $newUrl = implode('/', $segment);
            }
            else
                $newUrl = $routes[$key]['route'];

            $data = str_replace($this->base, '', $this->url) . '/' . $newUrl;
            return $this->getUrl($data, $secure);
        }
        else
            return $this->getUrl($this->url, $secure);
    }

    /**
    * Get assets directory for app.
    *
    * @return string
    */
    public function assets($data = null, $secure = false)
    {
        $data = (!is_null($data)) ? $this->url . '/' . ASSETS_FOLDER . '/' . $data : $this->url . '/' . ASSETS_FOLDER . '/';
        return $this->getUrl($data, $secure);
    }

    /**
    * Redirect to another URL.
    *
    * @return null
    */
    public function redirect($data = null, $statusCode = 301, $secure = false)
    {
        if(substr($data, 0, 4) == 'http' || substr($data, 0, 5) == 'https')
            header('Location: ' . $data, true, $statusCode);
        else
        {
            $data = (!is_null($data)) ? $this->url . '/' . $data : $this->url;
            header('Location: ' . $this->getUrl($data, $secure), true, $statusCode);
        }

        die();
    }

    /**
    * Get active URI.
    *
    * @return string | null
    */
    public function current()
    {
        return $this->scheme() . Http::server('HTTP_HOST') . Http::server('REQUEST_URI');
    }

    /**
    * Get segments of URI.
    *
    * @return string | null
    */
    public function segment($num = null)
    {
        if ( is_null(Http::server('REQUEST_URI')) || is_null(Http::server('SCRIPT_NAME')) )
            return null;

        if (!is_null($num))
        {
            $uri = $this->replace( str_replace($this->base, '', Http::server('REQUEST_URI')) );
            $uriA = explode('/', $uri);
            return (isset($uriA[$num]) ? $uriA[$num] : null);
        }
        else
            return null;
    }

    /**
    * Get url.
    *
    * @return string
    */
    protected function getUrl($data, $secure)
    {
        $this->https = $secure;
        return $this->scheme() . $this->replace($data);
    }

    /**
    * Get url scheme.
    *
    * @return string
    */
    protected function scheme()
    {
        if($this->cachedHttps === true)
            $this->https = true;

        return "http" . ($this->https === true ? 's' : '') . "://";
    }

    /**
    * Replace.
    *
    * @return string | null
    */
    private function replace($data)
    {
        return str_replace(array('///', '//'), '/', $data);
    }
}