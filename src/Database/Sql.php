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
        $debug = (APP_ENV === 'dev' ? true : false);
        
        $config = config('database');
        $config['cachedir'] = realpath(ROOT . '/storage/cache/sql/');
        if ($config['driver'] == 'sqlite') {
            if (strpos($config['database'], ':') === false) {
                $config['database'] = realpath(ROOT . '/storage/database/'. $config['database']);
            }
        }
        $config['debug'] = $debug;

        return parent::__construct($config);
    }

    /**
     * Call function for Class
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this, $method], $parameters);
    }
}
