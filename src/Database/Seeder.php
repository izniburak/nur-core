<?php

namespace Nur\Database;

use Illuminate\Database\Seeder as DatabaseSeeder;
use Illuminate\Support\Arr;
use Symfony\Component\Console\Output\ConsoleOutput;

abstract class Seeder extends DatabaseSeeder
{
    /**
     * @var ConsoleOutput
     */
    protected $console;

    /**
     * Seeder constructor.
     */
    public function __construct()
    {
        $this->console = new ConsoleOutput;
    }

    /**
     * Seed the given connection from the given path.
     *
     * @param  array|string  $class
     * @param  bool  $silent
     * @return $this
     */
    public function call($class, $silent = false)
    {
        $classes = Arr::wrap($class);
        foreach ($classes as $class) {
            $seed = $this->resolve($class);
            Model::unguarded(function() use ($seed) {
                $seed->setContainer(app())->__invoke();
            });
        }

        return $this;
    }

    /**
     * @param string $class
     *
     * @return DatabaseSeeder|mixed
     */
    protected function resolve($class)
    {
        if (!class_exists($class)) {
            $classFile = database_path("seeds/{$class}.php");
            require $classFile;
        }

        return app()->make($class);
    }
}
