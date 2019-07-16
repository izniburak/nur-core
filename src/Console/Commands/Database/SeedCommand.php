<?php

namespace Nur\Console\Commands\Database;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Nur\Database\Model;

class SeedCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('db:seed')
            ->addOption('--class', '-c', InputOption::VALUE_OPTIONAL, 'The class name of the root seeder.')
            ->addOption('--force', '-f', InputOption::VALUE_OPTIONAL, 'Force to re-create database file.')
            ->setDescription('Seed the database with records.')
            ->setHelp("Seed the database with records.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->hasParameterOption('--class') === false) {
            foreach (glob(database_path("seeds/*.php")) as $file) {
                $class = explode('.', end(explode('/', $file)))[0];
                $this->executeSeeder($class, $file);
                $output->writeln('<info>+Success!</info> Database seeding completed successfully. ['.$class.']');
            }

            return;
        }

        $class = $input->getOption('class');
        $classFile = database_path("seeds/{$class}.php");
        $this->executeSeeder($class, $classFile);
    }

    private function executeSeeder($class, $classFile)
    {
        if (file_exists($classFile) && !class_exists($class)) {
            require $classFile;
        }

        Model::unguarded(function() use ($class) {
            app()->make($class)->setContainer(app())->__invoke();
        });
    }
}
