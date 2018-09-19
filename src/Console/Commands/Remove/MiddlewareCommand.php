<?php

namespace Nur\Console\Commands\Remove;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
        $file = ROOT . '/app/Middlewares/' . $name . '.php';

        if (file_exists($file)) {
            unlink($file);
            $output->writeln(
                "\n" . ' <info>+Success!</info> "' . ($name) . '" middleware removed.'
            );
        } else {
            $output->writeln(
                "\n" . ' <error>-Error!</error> Middleware not found! (' . $name . ')'
            );
        }

        return;
    }
}
