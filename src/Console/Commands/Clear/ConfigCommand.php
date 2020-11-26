<?php

namespace Nur\Console\Commands\Clear;

use Nur\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('clear:config')
            ->setDescription('Clear the application configs cache files.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = cache_path('config.php');
        if (file_exists($file) && unlink($file)) {
            $output->writeln('<info>+Success!</info> Config cache file has been deleted.');
            return 0;
        }

        $output->writeln('<question>+Info!</question> There is no config cache file.');
        return 1;
    }
}
