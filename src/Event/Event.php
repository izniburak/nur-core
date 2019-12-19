<?php

namespace Nur\Event;

use Nur\Exception\ExceptionHandler;

class Event
{
    /**
     * Trigger an event
     *
     * @param string $event
     * @param array  $params
     * @param string $method
     *
     * @return void
     * @throws ExceptionHandler
     */
    public function trigger($event, array $params = [], $method = 'handle'): void
    {
        $listeners = config('services.listeners');
        $event = $listeners[$event];
        if (is_array($event)) {
            foreach ($event as $listener) {
                $this->validateAndRun($listener, $params, $method);
            }
            return;
        }

        $this->validateAndRun($event, $params, $method);
    }

    /**
     * @param $listener
     * @param $params
     * @param $method
     *
     * @return void
     * @throws ExceptionHandler
     */
    private function validateAndRun($listener, $params, $method): void
    {
        if (! class_exists($listener)) {
            throw new ExceptionHandler('Event class not found.', $listener);
        }

        if (! method_exists($listener, $method)) {
            throw new ExceptionHandler('Method not found in Event class.', $listener . '::' . $method . '()');
        }

        call_user_func_array([new $listener, $method], $params);
    }
}
