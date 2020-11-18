<?php

namespace Nur\Event;

interface EventInterface
{
    /**
     * This method will be triggered
     * when you called it through event() method.
     *
     * @return mixed
     */
    public function handle();
}