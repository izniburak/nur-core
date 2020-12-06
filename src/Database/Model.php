<?php

namespace Nur\Database;

use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * @mixin \Illuminate\Database\Query\Builder
 */
class Model extends EloquentModel
{
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
