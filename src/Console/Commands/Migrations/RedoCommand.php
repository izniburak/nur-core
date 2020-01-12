<?php

namespace Nur\Console\Commands\Migrations;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RedoCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('redo')
            ->addArgument('version', InputArgument::REQUIRED, 'The version number for the migration')
            ->setDescription('Redo a specific migration')
            ->setHelp(<<<EOT
The <info>redo</info> command redo a specific migration

<info>migration:redo 20191018185412</info>

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

        if (!in_array($version, $versions)) {
            return;
        }

        if (!isset($migrations[$version])) {
            $output->writeLn('<error>-Error!</error> "' . $version . '" migration not found. Please check migration ID.');
            return;
        }

        $container = $this->getContainer();
        $container['phpmig.migrator']->down($migrations[$version]);
        $container['phpmig.migrator']->up($migrations[$version]);
    }
}
