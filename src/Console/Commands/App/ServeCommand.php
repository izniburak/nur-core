<?php

namespace Nur\Console\Commands\App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class ServeCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('serve')
            ->setDescription("Start application on PHP Development Server (Default: 127.0.0.1:7070)")
            ->addOption('--port', '-p', InputOption::VALUE_OPTIONAL, 'Application running port (Default: 7070)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $port = $input->hasParameterOption('--port');
        $port = ($port !== false ? $input->getOption('port') : '7070');

        $output->writeln(
            "\n" . "<info>Nur Application's started on PHP Development Server (http://127.0.0.1:".$port."/)" . "\n" . 
            "Press Ctrl-C to Quit.</info>" . "\n"
        );
        passthru("php -S 127.0.0.1:".$port." -t " . getcwd());
    }
}
