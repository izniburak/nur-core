<?php
/**
* nur - a simple framework for PHP Developers
*
* @author   izni burak demirtaş (@izniburak) <izniburak@gmail.com>
* @web      <http://burakdemirtas.org>
* @url      <https://github.com/izniburak/nur>
* @license  The MIT License (MIT) - <http://opensource.org/licenses/MIT>
*/

namespace Nur\Load;

use Nur\Exception\ExceptionHandler;

class Load
{
    /**
     * Load view file.
     *
     * @param string $name 
     * @param array $data
     * @return void
     * 
     * @throw ExceptionHandler
     */
    public function view($name, $data = null)
    {
        $name = ($name);
        $file = realpath(ROOT . '/app/Views/' . $name . '.php');

        if (file_exists($file)) {
            if (is_array($data)) {
                extract($data);
            }
            require $file;
            return ob_get_clean();
        }
        
        return new ExceptionHandler('Oppss! File not found.', '<b>View::' . $name . '</b> not found.');
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
        if (file_exists($file)) {
            require $file;
            die();
        }
        else {
            die('<h2>' . $title . '</h2> ' . $message);
        }
    }

}
