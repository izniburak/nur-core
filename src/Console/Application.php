<?php

namespace Nur\Console;

class Application
{
    /**
     * @var \Nur\Console\Application
     */
    protected $app;

    /**
     * @var \Nur\Kernel\Application
     */
    protected $nur;

    /**
     * @var array Application base command list
     */
    protected $commandList = [
        Commands\App\UpCommand::class,
        Commands\App\DownCommand::class,
        Commands\App\StatusCommand::class,
        Commands\App\ServeCommand::class,
        Commands\App\KeygenCommand::class,

        Commands\Cache\RouteCommand::class,
        Commands\Cache\ConfigCommand::class,

        Commands\Clear\CacheCommand::class,
        Commands\Clear\LogCommand::class,
        Commands\Clear\RouteCommand::class,
        Commands\Clear\ConfigCommand::class,

        Commands\Make\ControllerCommand::class,
        Commands\Make\ModelCommand::class,
        Commands\Make\MiddlewareCommand::class,
        Commands\Make\ResourceCommand::class,
        Commands\Make\CommandCommand::class,
        Commands\Make\EventCommand::class,
        Commands\Make\SeederCommand::class,

        Commands\Database\CreateCommand::class,
        Commands\Database\RemoveCommand::class,
        Commands\Database\ListCommand::class,
        Commands\Database\SeedCommand::class,

        Commands\Remove\ControllerCommand::class,
        Commands\Remove\ModelCommand::class,
        Commands\Remove\MiddlewareCommand::class,
    ];

    /**
     * @var array Application migration tool Command list
     */
    protected $migrationCommands = [
        Commands\Migrations\CheckCommand::class,
        Commands\Migrations\DownCommand::class,
        Commands\Migrations\GenerateCommand::class,
        Commands\Migrations\MigrateCommand::class,
        Commands\Migrations\RedoCommand::class,
        Commands\Migrations\RollbackCommand::class,
        Commands\Migrations\StatusCommand::class,
        Commands\Migrations\UpCommand::class,
        Commands\Migrations\RemoveCommand::class,
    ];

    /**
     * @var array The commands provided by your application.
     */
    protected $commands = [];

    /**
     * Set console application.
     *
     * @param \Symfony\Component\Console\Application $consoleApp  Console Application
     * @param \Nur\Kernel\Application $app                        Nur Framework Application
     *
     * @return void
     */
    function __construct($consoleApp, $app)
    {
        $this->app = $consoleApp;
        $this->nur = $app;
        $this->generate();
    }

    /**
     * Genereta all Command class.
     *
     * @return void
     */
    public function generate()
    {
        // Create application commands
        foreach ($this->commandList as $key => $value) {
            $this->app->add(new $value);
        }

        // Create migration commands
        foreach ($this->migrationCommands as $command) {
            /**
             * @var \Symfony\Component\Console\Command\Command $newCommand
             */
            $newCommand = new $command;
            $newCommand->setName("migration:" . $newCommand->getName());
            $this->app->add($newCommand);
        }

        // Create custom application commands provided by user
        foreach ($this->commands as $key => $value) {
            $this->app->add(new $value);
        }
    }

    /**
     * Run console commands.
     *
     * @return void
     * @throws
     */
    public function run()
    {
        $this->app->run();
        exit();
    }
}
