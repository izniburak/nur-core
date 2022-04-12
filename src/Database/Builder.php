<?php

namespace Nur\Database;

use Illuminate\Database\Capsule\Manager as Capsule;

class Builder extends Capsule
{
    /**
     * Set Eloquent Capsule for Builder.
     */
    function __construct()
    {
        Eloquent::getInstance()->getCapsule();
        parent::__construct();
    }

    /**
     * Call functions for Class
     *
     * @param $method
     * @param $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return self::__callStatic($method, $parameters);
    }
}
