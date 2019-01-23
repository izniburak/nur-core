<?php

namespace Nur\Console\Commands\App;

use Symfony\Component\Console\Command\Command;
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
        if (! file_exists($file)) {
            touch($file);
            return $output->writeln('<info>+Success!</info> Nur Application was stopped.');
        }

        return $output->writeln("<error>+Error!</error> Nur Application's already stopped.");
    }
}
