<?php
/**
* nur - a simple framework for PHP Developers
*
* @author   izni burak demirtaş (@izniburak) <izniburak@gmail.com>
* @web      <http://burakdemirtas.org>
* @url      <https://github.com/izniburak/nur>
* @license  The MIT License (MIT) - <http://opensource.org/licenses/MIT>
*/
 
namespace Nur\Console\Commands\Migrations;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Config\FileLocator;
use Nur\Console\Commands\Migrations\AbstractCommand;

class RollbackCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('rollback')
             ->addOption('--target', '-t', InputArgument::OPTIONAL, 'The version number to rollback to')
             ->setDescription('Rollback last, or to a specific migration')
             ->setHelp(<<<EOT
The <info>rollback</info> command reverts the last migration, or optionally up to a specific version

<info>migration:rollback</info>
<info>migration:rollback -t 20111018185412</info>

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

        $version = $input->getOption('target');

        ksort($migrations);
        sort($versions);

        // Check we have at least 1 migration to revert
        if (empty($versions) || $version == end($versions)) {
            $output->writeln("<error>No migrations to rollback</error>");
            return;
        }

        // If no target version was supplied, revert the last migration
        if (null === $version) {
            // Get the migration before the last run migration
            $prev = count($versions) - 2;
            $version = $prev >= 0 ? $versions[$prev] : 0;
        } else {
            // Get the first migration number
            $first = reset($versions);

            // If the target version is before the first migration, revert all migrations
            if ($version < $first) {
                $version = 0;
            }
        }

        // Check the target version exists
        if (0 !== $version && !isset($migrations[$version])) {
            $output->writeln("<error>Target version ($version) not found</error>");
            return;
        }

        // Revert the migration(s)
        $container = $this->getContainer();
        krsort($migrations);
        foreach($migrations as $migration) {
            if ($migration->getVersion() <= $version) {
                break;
            }

            if (in_array($migration->getVersion(), $versions)) {
                $container['phpmig.migrator']->down($migration);
            }
        }
    }
}
