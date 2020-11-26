<?php

namespace Nur\Console\Commands\Remove;

use Nur\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MiddlewareCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('remove:middleware')
            ->addArgument('name', InputArgument::REQUIRED, 'The name for the middleware.')
            ->setDescription('Remove a middleware.')
            ->setHelp("This command makes you to remove middleware...");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $file = app_path('Middlewares/' . $name . '.php');

        if (file_exists($file)) {
            unlink($file);
            $output->writeln('<info>+Success!</info> "' . $name . '" middleware removed.');
            return 0;
        }

        $output->writeln('<error>-Error!</error> Middleware not found! (' . $name . ')');
        return 1;
    }
}
