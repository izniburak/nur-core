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

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Nur\Console\Commands\Migrations\AbstractCommand;

class CheckCommand extends AbstractCommand
{
    protected function configure()
    {
        parent::configure();

        $this->setName('check')
             ->setDescription('Check all migrations have been run, exit with non-zero if not')
             ->setHelp(<<<EOT
The <info>check</info> checks that all migrations have been run and exits with a
non-zero exit code if not, useful for build or deployment scripts.

<info>migration:check</info>

EOT
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->bootstrap($input, $output);
        $versions = $this->getAdapter()->fetchAll();
        $down = [];
        foreach($this->getMigrations() as $migration) {
            if (!in_array($migration->getVersion(), $versions)) {
                $down[] = $migration;
            }
        }

        if (!empty($down)) {
            $rows = [];
            foreach($down as $migration) {
                $status = '<error> DOWN </error>';
                $rows[] = [
                    $status, 
                    $migration->getVersion(), 
                    $migration->getName(), 
                    date('d M Y H:i:s', strtotime($migration->getVersion()))
                ];
            }

            $table = new Table($output);
            $table->setHeaders(array('Status', 'Migration ID', 'Migration Name', 'Created at'))
                ->setRows($rows)
            ;
            $table->render();
        }

        return 0;
    }
}
