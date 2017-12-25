<?php
/**
* nur - a simple framework for PHP Developers
*
* @author   izni burak demirtaş (@izniburak) <izniburak@gmail.com>
* @web      <http://burakdemirtas.org>
* @url      <https://github.com/izniburak/nur>
* @license  The MIT License (MIT) - <http://opensource.org/licenses/MIT>
*/

namespace Nur\Console\Commands\Clear;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LogCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('clear:log')
            ->setDescription("Clear the application log files.")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = ROOT . '/storage/log/';

        $count = 0;
        foreach(glob($path.'*.*') as $file) {
            if(!stristr($file, 'index.html')) {
                if(unlink($file)) {
                    $count++;
                }
            }
        }
            
        if($count > 0) {
            touch($file);
            $output->writeln(
                "\n" . ' <info>+Success!</info> '.$count.' log file(s) deleted.'
            );
        }
        else  {
            $output->writeln(
                "\n" . " <question>+Info!</question> There are no log files."
            );
        }

        return;
    }
}
