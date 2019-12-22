<?php

namespace Nur\Load;

use Nur\Exception\ExceptionHandler;

class Load
{
    /**
     * Load view file.
     *
     * @param string $name
     * @param array  $data
     *
     * @return mixed
     *
     * @throws ExceptionHandler
     */
    public function view($name, array $data = [])
    {
        $file = app_path('Views' . DIRECTORY_SEPARATOR . $name . '.php');
        if (file_exists($file)) {
            extract($data);
            require $file;
            return ob_get_clean();
        }

        throw new ExceptionHandler('Oppss! File not found.', 'View::' . $name . ' not found.');
    }

    /**
     * Load helper file.
     *
     * @param string $name
     * @param string $directory
     * @return mixed
     *
     * @throws ExceptionHandler
     */
    public function helper($name, $directory = 'Helpers')
    {
        $file = app_path($directory . DIRECTORY_SEPARATOR . $name . '.php');
        if (file_exists($file)) {
            return require $file;
        }

        throw new ExceptionHandler('Oppss! File not found.', 'Helper::' . $name . ' not found.');
    }
}
