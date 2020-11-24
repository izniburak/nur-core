<?php

namespace Nur\Uri;

use Nur\Http\Request;

class UriGenerator
{
    /**
     * @var string
     */
    protected $base;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var bool
     */
    protected $https = false;

    /**
     * @var bool
     */
    protected $cachedHttps = false;

    /**
     * @var array|mixed
     */
    protected $config;

    /**
     * @var Request
     */
    protected $request;

    /**
     * Create URI class values.
     *
     * @return string|null
     */
    function __construct()
    {
        $this->request = resolve(Request::class);
        $this->config = config('app');
        $this->base = app()->baseFolder();

        $this->url = $this->request->server('HTTP_HOST') . '/' . $this->base . '/';
        if (! in_array($this->request->server('HTTPS'), [null, 'off', 'false']) ||
            $this->request->server('SERVER_PORT') == 443 || $this->config['https'] === true) {
            $this->cachedHttps = true;
        }
    }

    /**
     * Get base url for app.
     *
     * @param string|null $path
     * @param bool        $secure
     *
     * @return string
     */
    public function base(string $path = null, $secure = false): string
    {
        $path = (! is_null($path)) ? $this->url . $path : $this->url . '/';
        return $this->getUrl($path, $secure);
    }

    /**
     * Get admin url for app.
     *
     * @param string|null $path
     * @param bool        $secure
     *
     * @return string
     */
    public function admin(string $path = null, $secure = false): string
    {
        return $this->base($this->config['admin'] . "/{$path}", $secure);
    }

    /**
     * Get route uri value with params.
     *
     * @param string $name
     * @param array  $params
     * @param bool   $secure
     *
     * @return string
     */
    public function route(string $name, array $params = [], $secure = false): string
    {
        $routes = file_exists(cache_path('routes.php'))
            ? require_once cache_path('routes.php')
            : app('route')->getRoutes();

        $found = false;
        foreach ($routes as $key => $value) {
            if ($value['alias'] == $name) {
                $found = true;
                break;
            }
        }

        if ($found) {
            if (strstr($routes[$key]['route'], ':')) {
                $segment = explode('/', $routes[$key]['route']);
                $i = 0;
                foreach ($segment as $key => $value) {
                    if (strstr($value, ':')) {
                        $segment[$key] = $params[$i];
                        $i++;
                    }
                }
                $newUrl = implode('/', $segment);
            } else {
                $newUrl = $routes[$key]['route'];
            }

            $data = str_replace($this->base, '', $this->url) . '/' . $newUrl;
            return $this->getUrl($data, $secure);
        }

        return $this->getUrl($this->url, $secure);
    }

    /**
     * Get assets directory for app.
     *
     * @param string|null $path
     * @param bool        $secure
     *
     * @return string
     */
    public function assets(string $path = null, $secure = false): string
    {
        return $this->base($this->config['assets'] . "/{$path}", $secure);
    }

    /**
     * Redirect to another URL.
     *
     * @param string|null $path
     * @param int         $statusCode
     * @param bool        $secure
     *
     * @return void
     */
    public function redirect(string $path = null, int $statusCode = 301, $secure = false): void
    {
        if (strpos($path, 'http') === 0) {
            header('Location: ' . $path, true, $statusCode);
        } else {
            header('Location: ' . $this->base($path, $secure), true, $statusCode);
        }

        die;
    }

    /**
     * Get active URI.
     *
     * @return string
     */
    public function current(): string
    {
        return $this->scheme() . $this->request->server('HTTP_HOST') . $this->request->server('REQUEST_URI');
    }

    /**
     * Get segments of URI.
     *
     * @param int|null $num
     *
     * @return array|string|null
     */
    public function segment(int $num = null)
    {
        if (is_null($this->request->server('REQUEST_URI')) || is_null($this->request->server('SCRIPT_NAME'))) {
            return null;
        }

        $uri = $this->replace(str_replace($this->base, '', $this->request->server('REQUEST_URI')));
        $segments = array_filter(explode('/', $uri), function ($segment) {
            return !empty($segment);
        });

        if (!is_null($num)) {
            return (isset($segments[$num]) ? reset(explode('?', $segments[$num])) : null);
        }

        return $segments;
    }

    /**
     * Get url.
     *
     * @param string $data
     * @param bool   $secure
     *
     * @return string
     */
    protected function getUrl(string $data, bool $secure): string
    {
        $this->https = $secure;
        return $this->scheme() . $this->replace($data);
    }

    /**
     * Get url scheme.
     *
     * @return string
     */
    protected function scheme(): string
    {
        if ($this->cachedHttps === true) {
            $this->https = true;
        }

        return "http" . ($this->https === true ? 's' : '') . "://";
    }

    /**
     * Replace.
     *
     * @param string $data
     *
     * @return string|null
     */
    private function replace(string $data): ?string
    {
        return str_replace(['///', '//'], '/', $data);
    }
}
