<?php

namespace Nur\Console\Commands\Database;

use Nur\Console\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('db:list')
            ->setDescription('List all sqlite databases.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dir = $mask = database_path() . '/';
        $mask = $dir . '*.sqlite*';
        $dbList = glob($mask);

        if (count($dbList) > 0) {
            $rows = [];
            foreach ($dbList as $file) {
                $filename = explode('.', str_replace($dir, '', $file));
                unset($filename[count($filename) - 1]);
                $filename = implode('.', $filename);
                $mb = false;
                $filesize = (filesize($file) / 1024);
                if ($filesize > 1024) {
                    $filesize = ($filesize / 1024);
                    $mb = true;
                }
                $rows[] = [
                    $filename,
                    end(explode('.', $file)),
                    $filesize . ($mb ? 'MB' : 'KB'),
                    date("d M Y H:i", filemtime($file)),
                ];
            }

            $table = new Table($output);
            $table->setHeaders(['Database', 'Type', 'Size', 'Created at'])
                ->setRows($rows);

            $table->render();
            return 1;
        }

        $output->writeln('No SQLite database yet.');
        return 0;
    }
}
