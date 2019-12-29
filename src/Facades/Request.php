<?php

namespace Nur\Facades;

use Nur\Http\Request as BaseRequest;
use Nur\Kernel\Facade;

/**
 * @method static string|array server($key = null, $default = null)
 * @method static bool hasHeader($key)
 * @method static string|array header($key = null, $default = null)
 * @method static string|null bearerToken()
 * @method static bool exists($key)
 * @method static bool has($key)
 * @method static array all($keys = null)
 * @method static string|array|null input($key = null, $default = null)
 * @method static bool isJson()
 * @method static mixed json($key = null, $default = null)
 * @method static array allFiles()
 * @method static void hasAny($keys)
 * @method static void anyFilled($keys)
 * @method static void filled($key)
 * @method static array keys()
 * @method static array only($keys)
 * @method static array except($keys)
 * @method static string|array post($key = null, $default = null)
 * @method static bool hasCookie($key)
 * @method static string|array cookie($key = null, $default = null)
 * @method static bool hasFile($key)
 * @method static array|null file($key = null, $default = null)
 * @method static string method()
 * @method static string root()
 * @method static string fullUrlWithQuery(array $query)
 * @method static string|array query($key = null, $default = null)
 * @method static string url()
 * @method static string fullUrl()
 * @method static string|null segment($index, $default = null)
 * @method static array segments()
 * @method static string decodedPath()
 * @method static string path()
 * @method static string ip()
 * @method static array ips()
 * @method static string userAgent()
 * @method static BaseRequest merge(array $input)
 * @method static BaseRequest replace(array $input)
 * @method static mixed get($key = null, $default = null)
 * @method static array toArray()
 * @method static bool expectsJson()
 * @method static bool ajax()
 * @method static bool pjax()
 * @method static bool acceptsAnyContentType()
 * @method static bool wantsJson()
 * @method static string|null prefers($contentTypes)
 * @method static bool matchesType($actual, $type)
 * @method static bool acceptsJson()
 * @method static bool accepts($contentTypes)
 * @method static bool acceptsHtml()
 * @method static string format($default = 'html')
 * @method static array validation(array $rules, array $data = null)
 * @method static string|array retrieveItem($source, $key, $default)
 * @method static \Symfony\Component\HttpFoundation\ParameterBag getInputSource()
 * @method static bool isEmptyString($key)
 * @method static bool isValidFile($file)
 *
 * @see \Nur\Http\Request
 */
class Request extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return BaseRequest::class;
    }
}
