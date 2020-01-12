<?php

namespace Nur\Console\Commands\App;

use Nur\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StatusCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('app:status')
            ->setDescription("The current state of the application.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!file_exists(storage_path('app.down'))) {
            return $output->writeln("Nur Application's running.");
        }

        return $output->writeln("Nur Application has been stopped.");
    }
}
