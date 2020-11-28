<?php

namespace Nur\Database;

use Illuminate\Database\Seeder as DatabaseSeeder;
use Illuminate\Support\Arr;
use Nur\Console\Command;
use Nur\Kernel\Application;
use Symfony\Component\Console\Output\ConsoleOutput;

abstract class Seeder extends DatabaseSeeder
{
    /**
     * @var \Nur\Kernel\Application
     */
    protected $container;

    /**
     * @var Command
     */
    protected $command;

    /**
     * @var ConsoleOutput
     */
    protected $console;

    /**
     * Seeder constructor.
     *
     * @param Application $container
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(Application $container)
    {
        $this->container = $container;
        $this->console = new ConsoleOutput;
        $this->command = $this->container->make(Command::class, ['app' => $container]);
    }

    /**
     * Seed the given connection from the given path.
     *
     * @param array|string $class
     * @param bool         $silent
     * @param array        $parameters
     *
     * @return $this
     */
    public function call($class, $silent = false, array $parameters = [])
    {
        $classes = Arr::wrap($class);

        foreach ($classes as $class) {
            $seeder = $this->resolve($class);
            $name = get_class($seeder);
            if ($silent === false && isset($this->console)) {
                $this->console->writeln("<info>Seeding:</info> {$name}");
            }
            $startTime = microtime(true);
            $seeder->__invoke();
            $runTime = number_format((microtime(true) - $startTime) * 1000, 2);
            if ($silent === false && isset($this->command)) {
                $this->console->writeln("<info>Seeded:</info> {$name} ({$runTime}ms)");
            }
        }

        return $this;
    }
}
