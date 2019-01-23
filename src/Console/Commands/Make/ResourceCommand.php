<?php

namespace Nur\Console\Commands\Make;

use Symfony\Component\Console\Command\Command;
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
        $file = app_path('Controllers/'.$name.'.php');

        if (! file_exists($file)) {
            $this->createNewFile($file, $name);
            return $output->writeln('<info>+Success!</info> "'.$name.'" resource controller created.');
        }

        if ($input->hasParameterOption('--force') !== false) {
            unlink($file);
            $this->createNewFile($file, $name);
            return $output->writeln('<info>+Success!</info> "'.$name.'" resource controller re-created.');
        }

        return $output->writeln('<error>-Error!</error> Resource Controller already exists! ('.$name.')');
    }

    private function createNewFile($file, $name)
    {
        $controller = ucfirst($name);
        $contents = <<<PHP
<?php

namespace App\Controllers;

class $controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Nur\Http\Response|string
     */
    public function main()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Nur\Http\Response|string
     */
    public function getCreate()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Nur\Http\Response
     */
    public function postStore()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int \$id
     * @return \Nur\Http\Response|string
     */
    public function getShow(\$id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int \$id
     * @return \Nur\Http\Response|string
     */
    public function getEdit(\$id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int \$id
     * @return \Nur\Http\Response
     */
    public function putUpdate(\$id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int \$id
     * @return \Nur\Http\Response
     */
    public function deleteDestroy(\$id)
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
