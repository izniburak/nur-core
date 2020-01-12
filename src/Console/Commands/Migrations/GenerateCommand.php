<?php

namespace Nur\Console\Commands\Migrations;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('generate')
            ->addArgument('name', InputArgument::REQUIRED, 'The name for the migration')
            ->addOption('--table', '-t', InputOption::VALUE_OPTIONAL, 'Migration Table name (Default: null)')
            ->setDescription('Generate a new migration')
            ->setHelp(<<<EOT
The <info>generate</info> command creates a new migration with the name and path specified

<info>migration:generate TestMigration</info>

EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->bootstrap($input, $output);
        $tableName = $input->hasParameterOption('--table') !== false
            ? $input->getOption('table')
            : '';

        $path = $this->container['phpmig.migrations_path'];
        $locator = new FileLocator([]);
        $path = $locator->locate($path, base_path(), $first = true);

        if (!is_writeable($path)) {
            throw new \InvalidArgumentException(sprintf(
                'The directory "%s" is not writeable',
                $path
            ));
        }

        $path = realpath($path);
        $migrationName = $this->transMigName($input->getArgument('name'));
        $basename = date('YmdHis') . '_' . $migrationName . '.php';
        $path = $path . DIRECTORY_SEPARATOR . $basename;
        if (file_exists($path)) {
            throw new \InvalidArgumentException(sprintf('The file "%s" already exists', $path));
        }

        $className = $this->migrationToClassName($migrationName);
        $schema = '$this';
        $blueprint = '$table';
        $contents = <<<PHP
<?php

use Nur\Database\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;

class $className extends Migration
{
    /* Do the migration */
    public function up()
    {
        {$schema}->schema->create('{$tableName}', function(Blueprint $blueprint) {
            {$blueprint}->increments('id');
            
            // your columns...
            
            {$blueprint}->timestamps();
        });
    }

    /* Undo the migration */
    public function down()
    {
        {$schema}->schema->dropIfExists('{$tableName}');
    }
}

PHP;
        if (false === file_put_contents($path, $contents)) {
            throw new \RuntimeException(sprintf('The file "%s" could not be written to', $path));
        }

        $output->writeln(
            '<info>+Success!</info> ' .
            '"' . str_replace([getcwd(), 'database', 'migrations', '/', '\\', '.php'], '',
                $path) . '" migration generated.'
        );

        return;
    }

    protected function transMigName($migrationName)
    {
        if (preg_match('/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/', $migrationName)) {
            return $migrationName;
        }
        return 'mig' . $migrationName;
    }
}
