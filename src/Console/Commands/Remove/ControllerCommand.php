<?php

namespace Nur\Console\Commands\Remove;

use Nur\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ControllerCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('remove:controller')
            ->addArgument('name', InputArgument::REQUIRED, 'The name for the controller.')
            ->setDescription('Remove a controller.')
            ->setHelp("This command makes you to remove controller...");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $file = app_path('Controllers/' . $name . '.php');

        if (file_exists($file)) {
            unlink($file);
            $output->writeln('<info>+Success!</info> "' . $name . '" controller removed.');
            return 1;
        }

        $output->writeln('<error>-Error!</error> Controller not found! (' . $name . ')');
        return 0;
    }
}
