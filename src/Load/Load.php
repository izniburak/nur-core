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
     * Libraries and Models
     * 
     * @var array
     */
    public $library, $model = [];

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
     * Load model controller file.
     *
     * @param string $name 
     * @param bool $autoLoad
     * @return object 
     * 
     * @throw ExceptionHandler
     */
    public function model($name, $autoLoad = false)
    {
        $name = ($name);
        $class = ($autoLoad ? $name : 'App\Models\\' . $name);
        $file = realpath(ROOT . '/app/Models/' . $name . '.php');

        if (file_exists($file)) {
            if(!class_exists($name)) {
                require $file;
            }
            if (!isset($this->model[$name])) {
                $this->model[$name] = new $class();
            }
            return $this->model[$name];
        }

        return new ExceptionHandler('Oppss! File not found.',  '<b>Model::' . $name . '</b> not found.');
    }

    /**
     * Load library class file.
     *
     * @param string $name 
     * @param mixed $params
     * @param bool $autoLoad
     * @return object
     * 
     * @throw ExceptionHandler
     */
    public function library($name, $params = null, $autoLoad = false)
    {
        $fileName = explode('/', $name);
        $fileName = end($fileName);
        $class = ($autoLoad ? $name : 'App\Libraries\\' . str_replace('/', '\\', $name));
        $file = realpath(ROOT . '/app/Libraries/' . $name . '.php');

        if (file_exists($file)) {
            if(!class_exists($name)) {
                require $file;
            }
            if (!isset($this->library[$name]) && is_null($params)) {
                $this->library[$name] = new $class();
            }
            elseif(!isset($this->library[$name]) && !is_null($params)) {
                if(is_array($params)) {
                    $this->library[$name] = new $class(...$params);
                }
                else 
                    $this->library[$name] = new $class($params);
            }
            return $this->library[$name];
        }

        return new ExceptionHandler('Oppss! File not found.',  '<b>Library::' . $name . '</b> not found.');
    }

    /**
     * Load helper file.
     *
     * @param string $name
     * @return void 
     * 
     * @throw ExceptionHandler
     */
    public function helper($name)
    {
        $name = ($name);
        $file = realpath(ROOT . '/app/Helpers/' . $name . '.php');

        if (file_exists($file)) {
            require $file;
            return;
        }
        
        return new ExceptionHandler('Oppss! File not found.', '<b>Helper::' . $name . '</b> not found.');
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

    /**
     * Class destructer
     *
     * @return void
     */
    public function __destruct()
    {
        $this->library = $this->model = [];
    }
}
