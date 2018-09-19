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
        $name = ($name);
        $file = app_path('Views' . DIRECTORY_SEPARATOR . $name . '.php');

        if (file_exists($file)) {
            extract($data);
            require $file;
            return ob_get_clean();
        }

        throw new ExceptionHandler('Oppss! File not found.', '<b>View::' . $name . '</b> not found.');
    }

    /**
     * Set Error Message and Display.
     *
     * @param string $title
     * @param string $msg
     * @param string $page
     *
     * @return void
     */
    public function error($title = null, $msg = null, $page = null)
    {
        $title = is_null($title) ? 'Oppss! System Error. ' : $title;
        $message = is_null($msg) ? 'Please check your codes.' : $msg;
        $page = is_null($page) ? 'index' : $page;

        $file = realpath(ROOT . '/app/Views/errors/' . $page . '.php');
        app_path('Views' . DIRECTORY_SEPARATOR . 'errors' . DIRECTORY_SEPARATOR . $page . '.php');
        if (file_exists($file)) {
            require $file;
            die();
        }

        die('<h2>' . $title . '</h2> ' . $message);
    }
}
