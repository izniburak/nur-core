<?php

namespace Nur\Console\Commands\Remove;

use Nur\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ModelCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('remove:model')
            ->addArgument('name', InputArgument::REQUIRED, 'The name for the model.')
            ->setDescription('Remove a model.')
            ->setHelp("This command makes you to remove model...");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $file = app_path('Models/' . $name . '.php');

        if (file_exists($file)) {
            unlink($file);
            $output->writeln('<info>+Success!</info> "' . $name . '" model removed.');
            return 1;
        }

        $output->writeln('<error>-Error!</error> Model not found! (' . $name . ')');
        return 0;
    }
}
