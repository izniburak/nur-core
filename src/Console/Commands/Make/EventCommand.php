<?php

namespace Nur\Console\Commands\Make;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class EventCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('make:event')
            ->addArgument('name', InputArgument::REQUIRED, 'The name for the event.')
            ->addOption('--force', '-f', InputOption::VALUE_OPTIONAL, 'Force to re-create event.')
            ->setDescription('Create a new event.')
            ->setHelp("This command makes you to create event...");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $file = app_path('Events/'.$name.'.php');

        if (! file_exists($file)) {
            $this->createNewFile($file, $name);
            return $output->writeln('<info>+Success!</info> "'.$name.'" event created.');
        }

        if ($input->hasParameterOption('--force') !== false) {
            unlink($file);
            $this->createNewFile($file, $name);
            return $output->writeln('<info>+Success!</info> "'.$name.'" event re-created.');
        }

        return $output->writeln('<error>-Error!</error> Event already exists! ('.$name.')');
    }

    private function createNewFile($file, $name)
    {
        $className = ucfirst($name);
        $contents = <<<PHP
<?php

namespace App\Events;

use Nur\Event\Base;

class $className extends Base
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
