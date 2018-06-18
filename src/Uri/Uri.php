<?php

namespace Nur\Uri;

use Nur\Uri\UriGenerator;

class Uri extends UriGenerator
{
    /**
     * Call function for Class
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if(empty($parameters)) {
            array_push($parameters, '');
        }

        if(strpos($method, 'secure') !== 0) {
            return call_user_func_array([$this, $method], $parameters);
        }
        else {
            array_push($parameters, true);
            $methodName = strtolower( str_replace('secure', '', $method) );
            return call_user_func_array([$this, $methodName], $parameters);
        }
    }
}