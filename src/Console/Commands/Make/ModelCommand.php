<?php

namespace Nur\Console\Commands\Make;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ModelCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('make:model')
            ->addArgument('name', InputArgument::REQUIRED, 'The name for the model.')
            ->addOption('--table', '-t', InputOption::VALUE_OPTIONAL, 'The table name for model.')
            ->addOption('--force', '-f', InputOption::VALUE_OPTIONAL, 'Force to re-create model.')
            ->setDescription('Create a new model.')
            ->setHelp("This command makes you to create model...");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $table = '';

        if ($input->hasParameterOption('--table')) {
            $table = $input->getOption('table');
        }

        $file = app_path('Models/'.$name.'.php');
        if (! file_exists($file)) {
            $this->createNewFile($file, $name, $table);
            return $output->writeln('<info>+Success!</info> "' . ($name) . '" model created.');
        }

        if ($input->hasParameterOption('--force') !== false) {
            unlink($file);
            $this->createNewFile($file, $name, $table);
            return $output->writeln('<info>+Success!</info> "' . ($name) . '" model re-created.');
        }

        return $output->writeln('<error>-Error!</error> Model already exists! (' . $name . ')');
    }

    private function createNewFile($file, $name, $tableName = '')
    {
        $model = ucfirst($name);
        $table = 'protected $table = \''.$tableName.'\';';
        $timestamps = 'public $timestamps = true;';
        $contents = <<<PHP
<?php

namespace App\Models;

use Nur\Database\Model;

class $model extends Model
{
    /**
     * Table Name
     *
     * @var string
     */
    $table
    
    /**
     * Timestamps
     *
     * @var bool
     */
    $timestamps
}

PHP;
        if (false === file_put_contents($file, $contents)) {
            throw new \RuntimeException(sprintf('The file "%s" could not be written to', $file));
        }
    }
}
