<?php

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
