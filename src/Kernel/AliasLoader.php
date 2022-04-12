<?php

namespace Nur\Kernel;

/**
 * Class AliasLoader
 * Adapted from Laravel Framework
 * @see https://github.com/laravel/framework/blob/8.x/src/Illuminate/Foundation/AliasLoader.php
 *
 * @package Nur\Kernel
 */
class AliasLoader
{
    /**
     * The array of class aliases.
     *
     * @var array
     */
    protected $aliases;

    /**
     * Indicates if a loader has been registered.
     *
     * @var bool
     */
    protected $registered = false;

    /**
     * The namespace for all real-time facades.
     *
     * @var string
     */
    protected static $facadeNamespace = 'Facades\\';

    /**
     * The singleton instance of the loader.
     *
     * @var static
     */
    protected static $instance;

    /**
     * Create a new AliasLoader instance.
     *
     * @param array $aliases
     *
     * @return void
     */
    private function __construct(array $aliases)
    {
        $this->aliases = $aliases;
    }

    /**
     * Get or create the singleton alias loader instance.
     */
    public static function getInstance(array $aliases = []): AliasLoader
    {
        if (is_null(static::$instance)) {
            return static::$instance = new static($aliases);
        }

        $aliases = array_merge(static::$instance->getAliases(), $aliases);

        static::$instance->setAliases($aliases);

        return static::$instance;
    }

    /**
     * Load a class alias if it is registered.
     */
    public function load(string $alias): bool
    {
        if (static::$facadeNamespace && strpos($alias, static::$facadeNamespace) === 0) {
            $this->loadFacade($alias);

            return true;
        }

        if (isset($this->aliases[$alias])) {
            return class_alias($this->aliases[$alias], $alias);
        }

        return false;
    }

    /**
     * Load a real-time facade for the given alias.
     */
    protected function loadFacade(string $alias): void
    {
        require $this->ensureFacadeExists($alias);
    }

    /**
     * Ensure that the given alias has an existing real-time facade class.
     */
    protected function ensureFacadeExists(string $alias): string
    {
        if (is_file($path = storage_path('cache/framework/facade-' . sha1($alias) . '.php'))) {
            return $path;
        }

        file_put_contents($path, $this->formatFacadeStub(
            $alias, file_get_contents(__DIR__ . '/stubs/facade.stub')
        ));

        return $path;
    }

    /**
     * Format the facade stub with the proper namespace and class.
     */
    protected function formatFacadeStub(string $alias, string $stub): string
    {
        $replacements = [
            str_replace('/', '\\', dirname(str_replace('\\', '/', $alias))),
            class_basename($alias),
            substr($alias, strlen(static::$facadeNamespace)),
        ];

        return str_replace(
            ['DummyNamespace', 'DummyClass', 'DummyTarget'], $replacements, $stub
        );
    }

    /**
     * Add an alias to the loader.
     */
    public function alias(string $alias, string $class): void
    {
        $this->aliases[$alias] = $class;
    }

    /**
     * Register the loader on the auto-loader stack.
     */
    public function register(): void
    {
        if (!$this->registered) {
            $this->prependToLoaderStack();

            $this->registered = true;
        }
    }

    /**
     * Prepend the load method to the auto-loader stack.
     */
    protected function prependToLoaderStack(): void
    {
        spl_autoload_register([$this, 'load'], true, true);
    }

    /**
     * Get the registered aliases.
     */
    public function getAliases(): array
    {
        return $this->aliases;
    }

    /**
     * Set the registered aliases.
     */
    public function setAliases(array $aliases): void
    {
        $this->aliases = $aliases;
    }

    /**
     * Indicates if the loader has been registered.
     */
    public function isRegistered(): bool
    {
        return $this->registered;
    }

    /**
     * Set the "registered" state of the loader.
     */
    public function setRegistered(bool $value): void
    {
        $this->registered = $value;
    }

    /**
     * Set the real-time facade namespace.
     */
    public static function setFacadeNamespace(string $namespace): void
    {
        static::$facadeNamespace = rtrim($namespace, '\\') . '\\';
    }

    /**
     * Set the value of the singleton alias loader.
     */
    public static function setInstance(AliasLoader $loader): void
    {
        static::$instance = $loader;
    }

    /**
     * Clone method.
     */
    private function __clone()
    {
        //
    }
}