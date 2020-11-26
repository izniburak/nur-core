<?php

namespace Nur\Console\Commands\Make;

use Nur\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ResourceCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('make:resource')
            ->addArgument('name', InputArgument::REQUIRED, 'The name for the resource controller.')
            ->addOption('--force', '-f', InputOption::VALUE_OPTIONAL, 'Force to re-create resource controller.')
            ->setDescription('Create a new resource controller.')
            ->setHelp("This command makes you to resource create controller...");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $file = app_path('Controllers/' . $name . '.php');

        if (!file_exists($file)) {
            $this->createNewFile($file, $name);
            $output->writeln('<info>+Success!</info> "' . $name . '" resource controller created.');
            return 0;
        }

        if ($input->hasParameterOption('--force') !== false) {
            unlink($file);
            $this->createNewFile($file, $name);
            $output->writeln('<info>+Success!</info> "' . $name . '" resource controller re-created.');
            return 0;
        }

        $output->writeln('<error>-Error!</error> Resource Controller already exists! (' . $name . ')');
        return 1;
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
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function main(): Response
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response|string
     */
    public function getCreate(): Response
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request \$request
     *
     * @return Response|string
     */
    public function postStore(Request \$request): Response
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int \$id
     *                
     * @return Response|string
     */
    public function getShow(int \$id): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int \$id
     *                
     * @return Response|string
     */
    public function getEdit(int \$id): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request \$request
     * @param int     \$id
     *                
     * @return Response
     */
    public function putUpdate(Request \$request, int \$id): Response
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int \$id
     *                
     * @return Response
     */
    public function deleteDestroy(int \$id): Response
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
