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
     * @return mixed
     */
    public function trigger($event, array $params = [], $method = 'handle')
    {
        $listeners = config('services.listeners');
        foreach ($listeners[$event] as $listener) {
            if (! class_exists($listener)) {
                throw new ExceptionHandler('Event class not found.', $listener);
            }

            if (! method_exists($listener, $method)) {
                throw new ExceptionHandler('Method not found in Event class.', $listener . '::' . $method . '()');
            }

            call_user_func_array([new $listener, $method], $params);
        }
    }
}
