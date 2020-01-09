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
    public function trigger(string $event, array $params = [], $method = 'handle'): void
    {
        $events = config('events');
        $event = $events[$event];
        if (is_array($event)) {
            foreach ($event as $e) {
                $this->validateAndRun($e, $params, $method);
            }
            return;
        }

        $this->validateAndRun($event, $params, $method);
    }

    /**
     * @param $event
     * @param $params
     * @param $method
     *
     * @return void
     * @throws ExceptionHandler
     */
    private function validateAndRun($event, $params, $method): void
    {
        if (! class_exists($event)) {
            throw new ExceptionHandler('Event class not found.', $event);
        }

        if (! method_exists($event, $method)) {
            throw new ExceptionHandler('Method not found in Event class.', $event . '::' . $method . '()');
        }

        call_user_func_array([new $event, $method], $params);
    }
}
