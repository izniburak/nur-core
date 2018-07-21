<?php

namespace Nur\Event;

use Nur\Exception\ExceptionHandler;

class Event
{
    /**
     * Trigger an event
     *
     * @param string $event
     * @param array $params
     * @param string $method
     * @return void
     */
    public function trigger($event, Array $params = [], $method = 'handle')
    {
        $listeners 	= config('services.listeners');
        foreach ($listeners[$event] as $listener) {
            if (!class_exists($listener)) {
                throw new ExceptionHandler('Listener class not found.', $listener);
            }

            if (!method_exists($listener, $method)) {
                throw new ExceptionHandler('Method not found in Listener class.', $listener . '::' . $method . '()');
            }

            return call_user_func_array([new $listener, $method], $params);
        }
    }
}
