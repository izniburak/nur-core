<?php

namespace Nur\Database;

use Illuminate\Database\Capsule\Manager as Capsule;
use Nur\Database\Eloquent;

class Builder extends Capsule
{
    /**
     * Set Eloquent Capsule for Builder.
     *
     * @return void
     */
    function __construct()
    {
        Eloquent::getInstance()->getCapsule();
        parent::__construct();
    }

    /**
     * Call function for Class
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return self::__callStatic($method, $parameters);
    }
}
