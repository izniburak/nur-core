<?php

namespace Nur\Console\Commands\Make;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ListenerCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('make:listener')
            ->addArgument('name', InputArgument::REQUIRED, 'The name for the listener.')
            ->addOption('--force', '-f', InputOption::VALUE_OPTIONAL, 'Force to re-create listener.')
            ->setDescription('Create a new listener.')
            ->setHelp("This command makes you to create listener...");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $file = app_path('Listeners/'.$name.'.php');

        if (! file_exists($file)) {
            $this->createNewFile($file, $name);
            return $output->writeln('<info>+Success!</info> "'.$name.'" listener created.');
        }

        if ($input->hasParameterOption('--force') !== false) {
            unlink($file);
            $this->createNewFile($file, $name);
            return $output->writeln('<info>+Success!</info> "'.$name.'" listener re-created.');
        }

        return $output->writeln('<error>-Error!</error> Listener already exists! ('.$name.')');
    }

    private function createNewFile($file, $name)
    {
        $className = ucfirst($name);
        $contents = <<<PHP
<?php

namespace App\Listeners;

use Nur\Event\Listener;

class $className extends Listener
{
    /**
     * This method will be triggered
     * when you called it through event() method.
     *
     * @return mixed
     */
    public function handle()
    {
        return true;
    }
}

PHP;
        if (false === file_put_contents($file, $contents)) {
            throw new \RuntimeException(sprintf('The file "%s" could not be written to', $file));
        }
    }
}
