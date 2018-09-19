<?php

namespace Nur\Components\Builder;

use Nur\Components\Builder\Providers\FormProvider;
use Nur\Uri\Uri;

class Form extends FormProvider
{
    /**
     * Class constructor
     *
     * @return mixed
     */
    public function __construct()
    {
        return parent::__construct(new Uri, new Html, csrf_token());
    }
}
