<?php

namespace Nur\Console;

class Command
{
    /**
     * @var \Symfony\Component\Console\Application
     */
    protected $app;

    /**
     * @var \Nur\Kernel\Application
     */
    protected $nur;

    protected $commandList = [
        'Nur\Console\Commands\App\UpCommand',
        'Nur\Console\Commands\App\DownCommand',
        'Nur\Console\Commands\App\StatusCommand',
        'Nur\Console\Commands\App\ServeCommand',
        'Nur\Console\Commands\App\KeygenCommand',

        'Nur\Console\Commands\Cache\RouteCommand',
        'Nur\Console\Commands\Cache\ConfigCommand',

        'Nur\Console\Commands\Clear\CacheCommand',
        'Nur\Console\Commands\Clear\LogCommand',
        'Nur\Console\Commands\Clear\RouteCommand',
        'Nur\Console\Commands\Clear\ConfigCommand',

        'Nur\Console\Commands\Make\ControllerCommand',
        'Nur\Console\Commands\Make\ModelCommand',
        'Nur\Console\Commands\Make\MiddlewareCommand',
        'Nur\Console\Commands\Make\ResourceCommand',
        'Nur\Console\Commands\Make\EventCommand',
        'Nur\Console\Commands\Make\SeederCommand',

        'Nur\Console\Commands\Database\CreateCommand',
        'Nur\Console\Commands\Database\RemoveCommand',
        'Nur\Console\Commands\Database\ListCommand',
        'Nur\Console\Commands\Database\SeedCommand',

        'Nur\Console\Commands\Remove\ControllerCommand',
        'Nur\Console\Commands\Remove\ModelCommand',
        'Nur\Console\Commands\Remove\MiddlewareCommand',
    ];

    protected $migrationCommands = [
        'Nur\Console\Commands\Migrations\CheckCommand',
        'Nur\Console\Commands\Migrations\DownCommand',
        'Nur\Console\Commands\Migrations\GenerateCommand',
        'Nur\Console\Commands\Migrations\MigrateCommand',
        'Nur\Console\Commands\Migrations\RedoCommand',
        'Nur\Console\Commands\Migrations\RollbackCommand',
        'Nur\Console\Commands\Migrations\StatusCommand',
        'Nur\Console\Commands\Migrations\UpCommand',
        'Nur\Console\Commands\Migrations\RemoveCommand',
    ];

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
        foreach ($this->commandList as $key => $value) {
            $this->app->add(new $value);
        }

        foreach ($this->migrationCommands as $command) {
            $newCommand = new $command;
            $newCommand->setName("migration:" . $newCommand->getName());
            $this->app->add($newCommand);
        }
    }

    /**
     * Run console commands.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        $this->app->run();
        exit();
    }
}
