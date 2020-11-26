<?php

namespace Nur\Console\Commands\Migrations;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('up')
            ->addArgument('version', InputArgument::REQUIRED, 'The version number for the migration')
            ->setDescription('Run a specific migration')
            ->setHelp(<<<EOT
The <info>up</info> command runs a specific migration

<info>migration:up 20191018185121</info>

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
        $versions = $this->getAdapter()->fetchAll();

        $version = $input->getArgument('version');

        if (in_array($version, $versions)) {
            $output->writeLn('<error>-Error!</error> "' . $version . '" migration status already active.');
            return 1;
        }

        if (!isset($migrations[$version])) {
            $output->writeLn('<error>-Error!</error> "' . $version . '" migration not found. Please check migration ID.');
            return 1;
        }

        $container = $this->getContainer();
        $container['phpmig.migrator']->up($migrations[$version]);

        return 0;
    }
}
