<?php

namespace Nur\Database;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Create Eloquent Model.
     *
     * @param array $attributes
     *
     * @return void
     */
    function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        Eloquent::getInstance()->getCapsule();
        Eloquent::getInstance()->getSchema();
    }
}
