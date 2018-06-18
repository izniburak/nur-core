<?php

namespace Nur\Database;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Nur\Database\Eloquent;

class Model extends EloquentModel
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Create Eloquent Model.
     *
     * @return void
     */
    function __construct()
    {
        Eloquent::getInstance()->getCapsule();
        Eloquent::getInstance()->getSchema();
    }
}
