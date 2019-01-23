<?php

namespace Nur\Console\Commands\Clear;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CacheCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('clear:cache')
            ->setDescription("Clear the application cache files. (View and SQL files)");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = cache_path();
        $count = 0;
        $folders = ['blade' => 'Blade', 'html' => 'Html', 'sql' => 'SQL'];
        foreach ($folders as $key => $value) {
            $files = $path . '/' . $key;
            foreach (glob($files . '/*.*') as $file) {
                if (! stristr($file, 'index.html')) {
                    if (unlink($file)) {
                        $count++;
                    }
                }
            }
        }

        if ($count > 0) {
            return $output->writeln('<info>+Success!</info> ' . $count . ' cache file(s) deleted.');
        }

        return $output->writeln('<question>+Info!</question> There are no cache files.');
    }
}
