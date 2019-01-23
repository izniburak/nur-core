<?php

namespace Nur\Database;

use Buki\Pdox as PdoxProvider;

class Sql extends PdoxProvider
{
    /**
     * Class constructor
     *
     * @return Buki\Pdox
     */
    public function __construct()
    {
        $config = config('database');
        $activeDb = $config['connections'][$config['default']];
        if ($activeDb['driver'] === 'sqlite') {
            $activeDb['database'] = database_path($activeDb['database']);
        }
        $activeDb['cachedir'] = cache_path('sql');
        $activeDb['debug'] = APP_ENV === 'dev';
        return parent::__construct($activeDb);
    }

    /**
     * Call function for Class
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this, $method], $parameters);
    }
}
