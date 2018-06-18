<?php
 
namespace Nur\Console\Commands\Migrations;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Nur\Console\Commands\Migrations\AbstractCommand;

class StatusCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('status')
             ->setDescription('Show the up/down status of all migrations')
             ->setHelp(<<<EOT
The <info>status</info> command prints a list of all migrations, along with their current status

EOT
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->bootstrap($input, $output);

        $rows = [];
        $versions = $this->getAdapter()->fetchAll();
        foreach($this->getMigrations() as $migration) {
            if (in_array($migration->getVersion(), $versions)) {
                $status = '<info>  UP  </info>';
                unset($versions[array_search($migration->getVersion(), $versions)]);
            } else {
                $status = '<error> DOWN </error>';
            }
            $rows[] = [
                $status, 
                $migration->getVersion(), 
                $migration->getName(), 
                date('d M Y H:i:s', strtotime($migration->getVersion()))
            ];
        }

        foreach($versions as $missing) {
            $rows[] = ['<info>  UP  </info>', $missing, '<error>** MISSING **</error>'];
        }

        $table = new Table($output);
        $table->setHeaders(array('Status', 'Migration ID', 'Migration Name', 'Created at'))
            ->setRows($rows)
        ;
        $table->render();

        return;
    }
}
