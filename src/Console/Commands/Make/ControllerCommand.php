<?php

namespace Nur\Console\Commands\Make;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ControllerCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('make:controller')
            ->addArgument('name', InputArgument::REQUIRED, 'The name for the controller.')
            ->addOption('--force', '-f', InputOption::VALUE_OPTIONAL, 'Force to re-create controller.')
            ->setDescription('Create a new controller.')
            ->setHelp("This command makes you to create controller...");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $file = app_path('Controllers/'.$name.'.php');

        if (! file_exists($file)) {
            $this->createNewFile($file, $name);
            return $output->writeln('<info>+Success!</info> "'.$name.'" controller created.');
        }

        if ($input->hasParameterOption('--force') !== false) {
            unlink($file);
            $this->createNewFile($file, $name);
            return $output->writeln('<info>+Success!</info> "'.$name.'" controller re-created.');
        }
        
        return $output->writeln('<error>-Error!</error> Controller already exists! ('.$name.')');
    }

    private function createNewFile($file, $name)
    {
        $controller = ucfirst($name);
        $contents = <<<PHP
<?php

namespace App\Controllers;

use Nur\Http\{Request, Response};

class $controller extends Controller
{
    /**
     * Main method for this controller.
     *
     * @param Request \$request
     *
     * @return Response|string
     */
    public function main(Request \$request): Response
    {
        //
    }
}

PHP;
        if (false === file_put_contents($file, $contents)) {
            throw new \RuntimeException(sprintf('The file "%s" could not be written to', $file));
        }
    }
}
