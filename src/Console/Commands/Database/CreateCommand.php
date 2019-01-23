<?php

namespace Nur\Console\Commands\Database;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('db:create')
            ->addArgument('name', InputArgument::REQUIRED, 'The name for the database.')
            ->addOption('--type', '-t', InputOption::VALUE_OPTIONAL, 'The type for database.')
            ->addOption('--force', '-f', InputOption::VALUE_OPTIONAL, 'Force to re-create database file.')
            ->setDescription('Create a sqlite database.')
            ->setHelp("This command makes you to create sqlite or sqlite3 database...");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        $databaseType = $input->hasParameterOption('--type') ? $input->getOption('type') : 'sqlite';
        $file = database_path($name.'.'.$databaseType);
        if (! file_exists($file)) {
            touch($file);
            return $output->writeln('<info>+Success!</info> "'.$name.'" '.$databaseType.' database created.');
        }

        if ($input->hasParameterOption('--force') !== false) {
            unlink($file);
            touch($file);
            return $output->writeln('<info>+Success!</info> "'.$name.'" '.$databaseType.' database re-created.');
        }

        return $output->writeln('<error>-Error!</error> Database already exists! ('.$name.'.'.$databaseType.')');
    }
}
