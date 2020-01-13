<?php

namespace Nur\Console\Commands\Make;

use Nur\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CommandCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('make:command')
            ->addArgument('name', InputArgument::REQUIRED, 'The name for the command.')
            ->addOption('--command', '-c', InputOption::VALUE_OPTIONAL, 'Command name of the your command.')
            ->addOption('--force', '-f', InputOption::VALUE_OPTIONAL, 'Force to re-create command.')
            ->setDescription('Create a new command.')
            ->setHelp('This command makes you to create a new command...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $commandName = $input->getOption('command');
        $file = app_path('Console/Commands/' . $name . '.php');

        if (!file_exists($file)) {
            $this->createNewFile($file, $name, $commandName);
            return $output->writeln('<info>+Success!</info> "' . $name . '" command created.');
        }

        if ($input->hasParameterOption('--force') !== false) {
            unlink($file);
            $this->createNewFile($file, $name, $commandName);
            return $output->writeln('<info>+Success!</info> "' . $name . '" command re-created.');
        }

        return $output->writeln('<error>-Error!</error> Command already exists! (' . $name . ')');
    }

    private function createNewFile($file, $name, $commandName)
    {
        $className = ucfirst($name);
        $commandName = $commandName ?? 'new:command';
        $contents = <<<PHP
<?php

namespace App\Console\Commands;

use Nur\Console\Command;
use Symfony\Component\Console\{
    Input\InputInterface,
    Output\OutputInterface
};

class $className extends Command
{
    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure()
    {
        \$this
            ->setName('$commandName')
            ->setDescription('Description of the Command')
            ->setHelp('Help description of the Command');
    }

    /**
     * Execute the command
     * 
     * @param InputInterface  \$input
     * @param OutputInterface \$output
     *
     * @return mixed|void
     */
    protected function execute(InputInterface \$input, OutputInterface \$output)
    {
        // your codes...
    }
}

PHP;
        if (false === file_put_contents($file, $contents)) {
            throw new \RuntimeException(sprintf('The file "%s" could not be written to', $file));
        }
    }
}
