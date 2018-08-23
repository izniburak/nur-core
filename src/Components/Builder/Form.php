<?php

namespace Nur\Components\Builder;

use Nur\Uri\Uri;
use Nur\Components\Builder\Html;
use Nur\Components\Builder\Providers\FormProvider;

class Form extends FormProvider
{
    /**
     * Class constructer
     * 
     * @return void
     */
    public function __construct()
    {
        return parent::__construct(new Uri, new Html, csrf_token());
    }
}
