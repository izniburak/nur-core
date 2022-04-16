<?php

namespace Nur\Event;

use Nur\Exception\ExceptionHandler;

class Event
{
    /**
     * @var array
     */
    private $events;

    /**
     * Event constructor.
     */
    public function __construct()
    {
        $this->events = config('events');
    }

    /**
     * Trigger an event
     *
     * @throws ExceptionHandler
     */
    public function trigger(string $event, array $params = [], string $method = 'handle'): void
    {
        $event = $this->events[$event] ?? null;
        if (!$event) {
            throw new ExceptionHandler('Event not found.', $event);
        }

        if (is_array($event)) {
            foreach ($event as $e) {
                $this->validateAndRun($e, $params, $method);
            }
            return;
        }

        $this->validateAndRun($event, $params, $method);
    }

    /**
     * @throws ExceptionHandler
     */
    private function validateAndRun(string $event, array $params, string $method): void
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
