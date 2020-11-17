<?php

namespace Nur\Log;

use Nur\Exception\ExceptionHandler;

class Log
{
    /**
     * Log file time format
     *
     * @var string
     */
    protected $timeFormat = 'Y-m-d H:i:s';

    /**
     * Save log as emergency
     *
     * @param mixed $message
     *
     * @return void
     */
    public function emergency($message): void
    {
        $this->log('emergency', $message);
    }

    /**
     * Save log as alert
     *
     * @param mixed $message
     *
     * @return void
     */
    public function alert($message): void
    {
        $this->log('alert', $message);
    }

    /**
     * Save log as critical
     *
     * @param mixed $message
     *
     * @return void
     */
    public function critical($message): void
    {
        $this->log('critical', $message);
    }

    /**
     * Save log as error
     *
     * @param mixed $message
     *
     * @return void
     */
    public function error($message): void
    {
        $this->log('error', $message);
    }

    /**
     * Save log as warning
     *
     * @param mixed $message
     *
     * @return void
     */
    public function warning($message): void
    {
        $this->log('warning', $message);
    }

    /**
     * Save log as notice
     *
     * @param mixed $message
     *
     * @return void
     */
    public function notice($message): void
    {
        $this->log('notice', $message);
    }

    /**
     * Save log as info
     *
     * @param mixed $message
     *
     * @return void
     */
    public function info($message): void
    {
        $this->log('info', $message);
    }

    /**
     * Save log as debug
     *
     * @param mixed $message
     *
     * @return void
     */
    public function debug($message): void
    {
        $this->log('debug', $message);
    }

    /**
     * Create log text to file
     *
     * @param string $level
     * @param mixed  $message
     *
     * @return void
     * @throws
     */
    protected function log(string $level, $message): void
    {
        if (is_array($message) || is_object($message)) {
            $message = print_r($message, true);
        }

        $text = '[' . date($this->timeFormat, time()) . ']['
                . strtoupper($level) . '] - [' . request()->ip() . '] --> ' . $message;

        $this->save($text);
    }

    /**
     * Save Log
     *
     * @param string $text
     *
     * @return void
     * @throws
     */
    protected function save(string $text): void
    {
        $fileName = 'log_' . date('Y-m-d') . '.log';
        $file = fopen(storage_path('log' . DIRECTORY_SEPARATOR . $fileName), 'a');

        if (fwrite($file, $text . "\n") === false) {
            throw new ExceptionHandler(
                'Oppss! Log file not created.',
                'Can you check chmod settings to save log file in log directory, please?'
            );
        }

        fclose($file);
    }
}
