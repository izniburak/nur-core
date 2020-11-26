<?php

namespace Nur\Console\Commands\Database;

use Nur\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('db:remove')
            ->addArgument('name', InputArgument::REQUIRED, 'The name for the database.')
            ->addOption('--type', '-t', InputOption::VALUE_OPTIONAL, 'The type for database.')
            ->setDescription('Remove a sqlite database.')
            ->setHelp("This command makes you to remove sqlite or sqlite3 database...");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        $databaseType = $input->hasParameterOption('--type') ? $input->getOption('type') : 'sqlite';
        $file = database_path("{$name}.{$databaseType}");
        if (file_exists($file)) {
            unlink($file);
            $output->writeln("<info>+Success!</info> '{$name}' {$databaseType} database removed.");
            return 0;
        }

        $output->writeln("<error>-Error!</error> Database not found! ({$name}.{$databaseType})");
        return 1;
    }
}
