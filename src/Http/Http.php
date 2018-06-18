<?php

namespace Nur\Http;

class Http
{
    /**
     * HTTP POST Request
     * 
     * @param string $key
     * @param bool $filter
     * @return string|null
     */
    public function post($key = null, $filter = false)
    {
        if(is_null($key)) {
            return $_POST;
        }
        $value = (isset($_POST[$key]) ? $_POST[$key] : null);

        return $this->filter($value, $filter);
    }

    /**
     * HTTP GET Request
     *
     * @param string $key
     * @param bool $filter
     * @return string|null
     */
    public function get($key = null, $filter = false)
    {
        if(is_null($key)) {
            return $_GET;
        }
        $value = (isset($_GET[$key]) ? $_GET[$key] : null);

        return $this->filter($value, $filter);
    }

    /**
     * HTTP PUT Request
     * 
     * @param string $key
     * @param bool $filter
     * @return string|null
     */
    public function put($key = null, $filter = true)
    {
      	parse_str(file_get_contents("php://input"), $_PUT);
      	if($key == null) {
            return $_PUT;
        }

        return $this->filter($_PUT[$key], $filter);
    }

    /**
     * HTTP DELETE Request
     * 
     * @param string $key
     * @param bool $filter
     * @return string|null
     */
    public function delete($key = null, $filter = true)
    {
      	parse_str(file_get_contents("php://input"), $_DELETE);
      	if($key == null) {
            return $_DELETE;
        }
      	
        return $this->filter($_DELETE[$key], $filter);
    }

    /**
     * HTTP REQUEST method. (Post or Get Request)
     * 
     * @param string $key
     * @param bool $filter
     * @return string|null
     */
    public function request($key = null, $filter = false)
    {
        if(is_null($key)) {
            return $_REQUEST;
        }
        $value = (isset($_REQUEST[$key]) ? $_REQUEST[$key] : null);

        return $this->filter($value, $filter);
    }

    /**
     * HTTP FILES Request
     * 
     * @param string $key
     * @param string $name
     * @return mixed
     */
    public function files($key = null, $name = null)
    {
        if(is_null($key)) {
            return $_FILES;
        }

        if (isset($_FILES[$key])) {
            if (!is_null($name)) {
                return $_FILES[$key][$name];
            }

            return $_FILES[$key];
        }

        return;
    }

    /**
     * HTTP SERVER Request
     * 
     * @param string $key
     * @return string|null
     */
    public function server($key = null)
    {
        if(is_null($key)) {
            return $_SERVER;
        }
        $key = strtoupper($key);

        return (isset($_SERVER[$key]) ? $_SERVER[$key] : null);
    }

    /**
     * Get current request method.
     *
     * @return string
     */
    public function method()
    {
        return $this->server('REQUEST_METHOD');
    }

    /**
     * Get Client IP Address.
     *
     * @return string
     */
    public function getClientIP()
    {
        $ip      = null;
        $client  = $this->server('HTTP_CLIENT_IP');
        $forward = $this->server('HTTP_X_FORWARDED_FOR');
        $remote  = $this->server('REMOTE_ADDR');

        if(filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        }
        else {
            $ip = $remote;
        }

        return $ip;
    }

    /**
     * Filter method for HTTP Values.
     * 
     * @param string $data
     * @param bool $filter
     * @return string|null
     */
    public function filter($data = null, $filter = false)
    {
        if(is_null($data)) {
            return null;
        }

        if(is_array($data)) {
            return array_map(function($value) use ($filter) { 
                return $this->filter($value, $filter);
            }, $data);
        }

        return ($filter == true ?  $this->xssClean($data) : trim($data));
    }

    /**
     * Clear XSS
     * 
     * @param string $data
     * @return string
     */
    public function xssClean($data)
    {
        // Fix &entity\n;
        $data = str_replace(['&amp;','&lt;','&gt;'], ['&amp;amp;','&amp;lt;','&amp;gt;'], $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);
        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);
        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);
        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

        do {
            // Remove really unwanted tags
            $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        }
        while ($old_data !== $data);
        // we are done...
        return $data;
    }
}
