<?php
/**
* nur - a simple framework for PHP Developers
*
* @author   izni burak demirtaş (@izniburak) <izniburak@gmail.com>
* @web      <http://burakdemirtas.org>
* @url      <https://github.com/izniburak/nur>
* @license  The MIT License (MIT) - <http://opensource.org/licenses/MIT>
*/

namespace Nur\Exception;

use Exception;
use Nur\Facades\Load;

class ExceptionHandler
{
    /**
     * Create Exception Class.
     *
     * @param string $title 
     * @param string $message
     * @return void
     */
    public function __construct($title, $message)
    {
        $debug = (APP_ENV == 'dev' ? true : false);
        if($debug){
            throw new Exception(strip_tags($title . ' - ' . $message), 1);
        }
        else {
            Load::error($title, $message);
        }
    }
}
