<?php

namespace Nur\Console\Commands\App;

use Nur\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

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
        chdir(public_path());

        $host = $input->hasParameterOption('--host') !== false ? $input->getOption('host') : '127.0.0.1';
        $port = $input->hasParameterOption('--port') !== false ? $input->getOption('port') : '8000';

        $output->writeln(
            "<info>Nur Application started on built-in PHP web server</info> http://{$host}:{$port}" . PHP_EOL .
            "Press Ctrl-C to Quit." . PHP_EOL
        );

        // passthru("php -S {$host}:{$port} " . base_path('server.php'));
        $process = new Process($this->serverCommand($host, $port), null, collect($_ENV)->mapWithKeys(function ($value, $key) {
            return in_array($key, ['APP_ENV']) ? [$key => $value] : [$key => false];
        })->all());

        $process->start(function ($type, $buffer) {
            $this->output->write($buffer);
        });

        while ($process->isRunning()) {
            usleep(500 * 1000);
        }

        return $process->getExitCode();
    }

    /**
     * Get the full server command.
     *
     * @param string $host
     * @param string $port
     *
     * @return array
     */
    protected function serverCommand($host, $port)
    {
        return [
            (new PhpExecutableFinder)->find(false),
            '-S',
            $host.':'.$port,
            base_path('server.php'),
        ];
    }
}
