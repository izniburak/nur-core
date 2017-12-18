<?php
/**
* nur - a simple framework for PHP Developers
*
* @author   izni burak demirtaş (@izniburak) <izniburak@gmail.com>
* @web      <http://burakdemirtas.org>
* @url      <https://github.com/izniburak/nur>
* @license  The MIT License (MIT) - <http://opensource.org/licenses/MIT>
*/

namespace Nur\Log;

use Nur\Exception\ExceptionHandler;

class Log
{
    /**
     * Save log as emergency
     *
     * @param mixed $message
     * @return void
     */
    public function emergency($message)
    {
        $this->write('emergency', $message);
    }

    /**
     * Save log as alert
     *
     * @param mixed $message
     * @return void
     */
    public function alert($message)
    {
        $this->write('alert', $message);
    }

    /**
     * Save log as critical
     *
     * @param mixed $message
     * @return void
     */
    public function critical($message)
    {
        $this->write('critical', $message);
    }

    /**
     * Save log as error
     *
     * @param mixed $message
     * @return void
     */
    public function error($message)
    {
        $this->write('error', $message);
    }

    /**
     * Save log as warning
     *
     * @param mixed $message
     * @return void
     */
    public function warning($message)
    {
        $this->write('warning', $message);
    }

    /**
     * Save log as notice
     *
     * @param mixed $message
     * @return void
     */
    public function notice($message)
    {
        $this->write('notice', $message);
    }

    /**
     * Save log as info
     *
     * @param mixed $message
     * @return void
     */
    public function info($message)
    {
        $this->write('info', $message);
    }

    /**
     * Save log as debug
     *
     * @param mixed $message
     * @return void
     */
    public function debug($message)
    {
        $this->write('debug', $message);
    }

    /**
     * Write logs to file
     *
     * @param string $level
     * @param mixed $message
     * @return void
     */
    protected function write($level, $message)
    {
        if (is_array($message)) {
            $message = serialize($message);
        }

        $text = '['.date('Y-m-d H:i:s').'] - ['.strtoupper($level).'] - ['.IP_ADDRESS.'] --> ' . $message;
        $this->save($text);
    }

    /**
     * Save Log
     *
     * @param string $text
     * @return void
     */
    protected function save($text)
    {
        $fileName 	= 'log_' . date('Y-m-d') . '.log';
        $file 		= fopen(ROOT . '/storage/log/' . $fileName, 'a');

        if(fwrite($file, $text . "\n") === false)
            throw new ExceptionHandler("Hata", "Log dosyası oluşturulamadı. Yazma izinlerini kontrol ediniz.");

        fclose($file);
    }
}