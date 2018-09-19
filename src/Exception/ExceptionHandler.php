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
        if (APP_ENV === 'dev') {
            throw new Exception(strip_tags($title . ' - ' . $message), 1);
        }

        return error($title, $message);
    }
}
