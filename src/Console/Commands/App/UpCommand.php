<?php

namespace Nur\Console\Commands\App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('app:up')
            ->setDescription("Bring the application out of maintenance mode.")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = ROOT . '/app.down';

        if(file_exists($file)) {
            unlink($file);
            $output->writeln(
                "\n" . ' <info>+Success!</info> Nur Application was started.'
            );
        }
        else {
            $output->writeln(
                "\n" . " <error>+Error!</error> Nur Application's already started."
            );
        }
         
        return;
    }
}
