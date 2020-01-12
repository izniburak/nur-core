<?php

namespace Nur\Console\Commands\Clear;

use Nur\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RouteCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('clear:route')
            ->setDescription('Clear the application routes cache files.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cacheFile = cache_path('routes.php');
        if (file_exists($cacheFile) && unlink($cacheFile)) {
            return $output->writeln('<info>+Success!</info> Routes cache file has been deleted.');
        }

        return $output->writeln('<question>+Info!</question> There is no route cache file.');
    }
}
