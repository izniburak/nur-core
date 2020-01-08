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
     * @param int    $code
     *
     * @return mixed
     */
    public function __construct(
        $title = 'System Error',
        $message = 'Whoops, something went wrong on the system.',
        $code = 1
    ) {
        if (!app()->isProduction()) {
            return parent::__construct(strip_tags($title . ' - ' . $message), $code);
        }

        return require __DIR__ . '/views/index.php';
    }
}
