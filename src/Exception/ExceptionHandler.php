<?php

namespace Nur\Exception;

use Exception;

class ExceptionHandler extends Exception
{
    /**
     * Create Exception Class.
     *
     * @param string $title
     * @param string $message
     *
     * @return mixed
     * @throws Exception
     */
    public function __construct($title, $message)
    {
        if (config('app.env') !== 'prod') {
            throw new Exception(strip_tags($title . ' - ' . $message), 1);
        }

        return require __DIR__ . '/views/index.php';
    }
}
