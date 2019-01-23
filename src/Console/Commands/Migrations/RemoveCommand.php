<?php

namespace Nur\Console\Commands\Migrations;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Config\FileLocator;
use Nur\Console\Commands\Migrations\AbstractCommand;

class RemoveCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('remove')
             ->addArgument('version', InputArgument::REQUIRED, 'The version number for the migration')
             ->setDescription('Remove a specific migration')
             ->setHelp(<<<EOT
The <info>up</info> command removes a specific migration

EOT
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->bootstrap($input, $output);

        $migrations = $this->getMigrations();
        $versions   = $this->getAdapter()->fetchAll();

        $version = $input->getArgument('version');

        if (in_array($version, $versions)) {
            $output->writeLn('<error>-Error!</error> "'.$version.'" migration is active. Please down it first.');
            return;
        }

        if (!isset($migrations[$version])) {
            $output->writeLn('<error>-Error!</error> "'.$version.'" migration not found. Please check migration ID.');
            return;
        }

        $mask = glob(database_path('migrations/'.$version.'_*.php'));
        array_map('unlink', $mask);
        $output->writeLn('<info>+Success!</info> "'.$version.'" migration removed.');
    }
}
