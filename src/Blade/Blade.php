<?php

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
     * Class constructor and create Blade Template Engine.
     *
     * @return void
     */
    public function __construct()
    {
        $cache = cache_path('blade');

        if (! is_dir(realpath($cache))) {
            mkdir($cache, 0755);
            touch($cache . "/index.html");
        }

        $views = app_path('Views');
        $this->class = new BladeTemplate($views, $cache);
    }

    /**
     * Display view file.
     *
     * @param string $view
     * @param array  $data
     * @param array  $mergeData
     *
     * @return string|false
     */
    public function make($view, $data = [], $mergeData = [])
    {
        if (is_string($view)) {
            return $this->class->view()->make($view, $data, $mergeData)->render();
        }

        return false;
    }

    /**
     * Class destructor
     *
     * @return void
     */
    public function __destruct()
    {
        $this->class = null;
    }
}
