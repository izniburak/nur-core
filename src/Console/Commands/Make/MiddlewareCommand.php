<?php

namespace Nur\Console\Commands\Make;

use Nur\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MiddlewareCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('make:middleware')
            ->addArgument('name', InputArgument::REQUIRED, 'The name for the middleware.')
            ->addOption('--force', '-f', InputOption::VALUE_OPTIONAL, 'Force to re-create middleware.')
            ->setDescription('Create a new middleware.')
            ->setHelp("This command makes you to create middleware...");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $file = app_path('Middlewares/' . $name . '.php');

        if (!file_exists($file)) {
            $this->createNewFile($file, $name);
            $output->writeln('<info>+Success!</info> "' . $name . '" middleware created.');
            return 1;
        }

        if ($input->hasParameterOption('--force') !== false) {
            unlink($file);
            $this->createNewFile($file, $name);
            $output->writeln('<info>+Success!</info> "' . $name . '" middleware re-created.');
            return 1;
        }

        $output->writeln('<error>-Error!</error> Middleware already exists! (' . $name . ')');
        return 0;
    }

    private function createNewFile($file, $name)
    {
        $middleware = ucfirst($name);
        $contents = <<<PHP
<?php

namespace App\Middlewares;

use Nur\Middleware\Middleware;

class $middleware extends Middleware
{
    /**
     * This method will be triggered
     * when the middleware is called 
     *
     * @return mixed
     */
    public function handle()
    {
        // your code...
        
        return true;
    }
}

PHP;
        if (false === file_put_contents($file, $contents)) {
            throw new \RuntimeException(sprintf('The file "%s" could not be written to', $file));
        }
    }
}
