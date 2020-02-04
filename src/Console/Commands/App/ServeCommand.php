<?php

namespace Nur\Console\Commands\App;

use Nur\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ServeCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('serve')
            ->setDescription("Start application on PHP Development Server (Default: 127.0.0.1:8000)")
            ->addOption('--port', '-p', InputOption::VALUE_OPTIONAL, 'Application running port (Default: 8000)')
            ->addOption('--host', '-s', InputOption::VALUE_OPTIONAL, 'Application running host (Default: 127.0.0.1)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $host = $input->hasParameterOption('--host') !== false ? $input->getOption('host') : '127.0.0.1';
        $port = $input->hasParameterOption('--port') !== false ? $input->getOption('port') : '8000';

        $output->writeln(
            "<info>Nur Application's started on built-in PHP web server ({$host}:{$port})" . PHP_EOL .
            "Press Ctrl-C to Quit.</info>" . PHP_EOL
        );
        passthru("php -S {$host}:{$port} server.php");
    }
}
