<?php
/**
* nur - a simple framework for PHP Developers
*
* @author   izni burak demirtaş (@izniburak) <izniburak@gmail.com>
* @web      <http://burakdemirtas.org>
* @url      <https://github.com/izniburak/nur>
* @license  The MIT License (MIT) - <http://opensource.org/licenses/MIT>
*/

namespace Nur\Components\Builder;

use Nur\Uri\Uri;
use Nur\Components\Builder\Providers\HtmlProvider;

class Html extends HtmlProvider
{
    /**
     * Class constructer
     * 
     * @return void
     */
    public function __construct()
    {
        return parent::__construct(new Uri);
    }
}
