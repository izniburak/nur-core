<?php

namespace Nur\Database;

use Buki\Pdox as PdoxProvider;

class Sql extends PdoxProvider
{
    /**
     * Class constructer
     *
     * @return Buki\Pdox
     */
    public function __construct()
    {
        $config = config('database');
        $config['cachedir'] = cache_path('sql');
        if ($config['driver'] == 'sqlite') {
            if (strpos($config['database'], ':') === false) {
                $config['database'] = database_path($config['database']);
            }
        }
        $config['debug'] = APP_ENV === 'dev';
        return parent::__construct($config);
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
