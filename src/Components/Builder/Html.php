<?php

namespace Nur\Components\Builder;

use Nur\Components\Builder\Providers\HtmlProvider;
use Nur\Uri\Uri;

class Html extends HtmlProvider
{
    /**
     * Class constructor
     *
     * @return mixed
     */
    public function __construct()
    {
        return parent::__construct(new Uri);
    }
}
