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
            $seeder = $this->resolve($class);
            if ($silent === false && isset($this->console)) {
                $this->console->writeln('<info>Seeding:</info> '.get_class($seeder));
            }
            $seeder->__invoke();
        }

        return $this;
    }
}
