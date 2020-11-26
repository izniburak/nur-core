<?php

namespace Nur\Console\Commands\App;

use Nur\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DownCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('app:down')
            ->setDescription("Put the application into maintenance mode.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = storage_path('app.down');
        if (!file_exists($file)) {
            touch($file);
            $output->writeln('<info>+Success!</info> Nur Application was stopped.');
            return 0;
        }

        $output->writeln("<error>+Error!</error> Nur Application's already stopped.");
        return 1;
    }
}
