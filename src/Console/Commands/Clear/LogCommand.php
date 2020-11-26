<?php

namespace Nur\Console\Commands\Clear;

use Nur\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LogCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('clear:log')
            ->setDescription('Clear the application log files.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = storage_path('log');
        $count = 0;
        foreach (glob("{$path}/*.*") as $file) {
            if (!stristr($file, 'index.html') && unlink($file)) {
                $count++;
            }
        }

        if ($count > 0) {
            $output->writeln("<info>+Success!</info> {$count} log file(s) deleted.");
            return 0;
        }

        $output->writeln('<question>+Info!</question> There are no log files.');
        return 1;
    }
}
