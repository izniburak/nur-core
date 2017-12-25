<?php
/**
* nur - a simple framework for PHP Developers
*
* @author   izni burak demirtaş (@izniburak) <izniburak@gmail.com>
* @web      <http://burakdemirtas.org>
* @url      <https://github.com/izniburak/nur>
* @license  The MIT License (MIT) - <http://opensource.org/licenses/MIT>
*/

namespace Nur\Blade;

use Nur\Blade\BladeRegister as BladeTemplate;

class Blade
{
    /**
     * @var Nur\Blade\BladeRegister
     */
    protected $class;

    /**
     * @var string
     */
    protected $templateFolder = '/storage/cache/blade';

    /**
     * Class constructer and create Blade Template Engine.
     *
     * @return void
     */
    public function __construct()
    {
        $cache = ROOT . $this->templateFolder;

        if(!is_dir(realpath($cache))) {
            mkdir($cache, 0755);
            touch($cache . "/index.html");
        }
        $views = realpath(ROOT . '/app/Views');
        $this->class = new BladeTemplate($views, $cache);
    }

    /**
     * Display view file.
     *
     * @param string $view
     * @param array $data 
     * @param array $mergeData
     * @return string|false
     */
    public function make($view, $data = [], $mergeData = [])
    {
        if(is_string($view)) {
            return $this->class->view()->make($view, $data, $mergeData)->render();
        }

        return false;
    }

    /**
     * Class destructer
     *
     * @return void
     */
    public function __destruct()
    {
        $this->class = null;
    }
}
