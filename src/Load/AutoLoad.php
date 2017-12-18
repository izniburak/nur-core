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

use Nur\Load\Load;

class AutoLoad
{
    /**
     * Autoload library from config
     * 
     * @var array  
     */
    protected $autoload = [];

    /**
     * Load class
     * 
     * @var Nur\Load\Load  
     */
    protected $load;

    public function __construct(Load $load)
    {
        $this->autoload = config('autoload');
        $this->load = $load;
        $this->helper(); 
        $this->library(); 
        $this->model();
    }

    /**
     * Auto load helper files.
     *
     * @return void
     */
    private function helper()
    {
        if (isset($this->autoload['helper'])) {
            foreach ($this->autoload['helper'] as $helper) {
                $this->load->helper($helper);
            }
        } 
    }

    /**
     * Auto load library class.
     *
     * @return void
     */
    private function library()
    {
        if (isset($this->autoload['library'])) {
            foreach ($this->autoload['library'] as $key => $library) {
                if(is_array($library)) {
                    $this->load->library[$key] = $this->load->library($key, $library, true);
                }
                else {
                    if(!is_int($key)) {
                        $this->load->library[$key] = $this->load->library($key, $library, true);
                    }
                    else {
                        $this->load->library[$library] = $this->load->library($library, null, true);
                    }
                }
            }
        }
    }

    /**
     * Auto load model class. 
     *
     * @return void
     */
    private function model()
    {
        if (isset($this->autoload['model'])) {
            foreach ($this->autoload['model'] as $model) {
                $this->load->model[$model] = $this->load->model($model, true);
            }
        }
    }

    /**
     * Class destructer
     *
     * @return void
     */
    function __destruct()
    {
        $this->autoload = $this->load = null;
    }
}
