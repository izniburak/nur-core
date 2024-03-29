<?php

namespace Nur\Kernel;

use Closure;
use Dotenv\Dotenv;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\CircularDependencyException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\{Arr, Collection, Env, Str};
use Nur\Container\Container;
use Nur\Exception\ExceptionHandler;
use RuntimeException;
use Whoops\Run as WhoopsRun;

/**
 * Class Application
 * Implemented from Laravel Framework in order to use some methods
 * @see https://github.com/laravel/framework/blob/8.x/src/Illuminate/Foundation/Application.php
 *
 * @package Nur\Kernel
 */
class Application extends Container
{
    /**
     * Nur Framework Version
     *
     * @var string
     */
    const VERSION = '3.2.1';

    /**
     * The base path for the Nur Framework installation.
     *
     * @var string
     */
    protected $basePath;

    /**
     * Indicates if the application has been bootstrapped before.
     *
     * @var bool
     */
    protected $hasBeenBootstrapped = false;

    /**
     * Indicates if the application has "booted".
     *
     * @var bool
     */
    protected $booted = false;

    /**
     * The array of booting callbacks.
     *
     * @var array
     */
    protected $bootingCallbacks = [];

    /**
     * The array of booted callbacks.
     *
     * @var array
     */
    protected $bootedCallbacks = [];

    /**
     * The array of terminating callbacks.
     *
     * @var array
     */
    protected $terminatingCallbacks = [];

    /**
     * All the registered service providers.
     *
     * @var array
     */
    protected $serviceProviders = [];

    /**
     * The names of the loaded service providers.
     *
     * @var array
     */
    protected $loadedProviders = [];

    /**
     * The deferred services and their providers.
     *
     * @var array
     */
    protected $deferredServices = [];

    /**
     * The custom database path defined by the developer.
     *
     * @var string
     */
    protected $databasePath;

    /**
     * The custom storage path defined by the developer.
     *
     * @var string
     */
    protected $storagePath;

    /**
     * The application namespace.
     *
     * @var string
     */
    protected $namespace;

    /**
     * The environment file to load during bootstrapping.
     *
     * @var string
     */
    protected $environmentFile = '.env';

    /**
     * The custom environment path defined by the developer.
     *
     * @var string
     */
    protected $environmentPath;

    /**
     * Framework core providers
     *
     * @var array
     */
    protected $registerCoreProviders = [];

    /**
     * Framework core aliases
     *
     * @var array
     */
    protected $registerCoreAliases = [];

    /**
     * Framework config
     *
     * @var array|null
     */
    public $config = null;

    /**
     * Framework root folder
     *
     * @var string|null
     */
    protected $root = null;

    /**
     * Framework document root folder
     *
     * @var string|null
     */
    protected $docRoot = null;

    /**
     * Framework base folder
     *
     * @var string|null
     */
    protected $baseFolder = null;

    /**
     * Public folder
     *
     * @var string
     */
    protected $publicFolder = 'public';

    /**
     * Create a new Nur application instance.
     *
     * @return void
     */
    public function __construct()
    {
        $pattern = "#/{$this->publicFolder}$#si";
        $this->root = preg_replace($pattern, '', realpath(getcwd()));
        $this->docRoot = preg_replace($pattern, '', realpath($_SERVER['DOCUMENT_ROOT']));
        $this->baseFolder = trim(
            str_replace(['\\'], ['/'], str_replace($this->docRoot, '', $this->root) . '/'),
            '/'
        );

        $this->init();
        $this->setBasePath($this->root);
        $this->registerBaseBindings();
        $this->registerBaseServiceProviders();
        $this->registerCoreContainerAliases();
        $this->registerApplicationProviders();
        $this->registerApplicationAliases();
    }

    /**
     * Kernel start
     *
     * @param string $env
     *
     * @return void
     * @throws ExceptionHandler
     */
    public function start(string $env): void
    {
        ini_set('log_errors', true);
        ini_set('error_log', $this->storagePath('log/nur.log'));
        error_reporting(E_ALL);
        date_default_timezone_set($this->config['app']['timezone']);
        switch ($env) {
            case 'local':
                ini_set('display_errors', 1);
                $this->initWhoops();
                break;
            case 'test':
            case 'production':
                ini_set('display_errors', 0);
                break;
            default:
                http_response_code(503);
                die('The application environment is not set correctly.');
        }

        if ($this->isDownForMaintenance()) {
            throw new ExceptionHandler('System is under maintenance.', 'We will be back very soon.');
        }

        if (!$this->hasBeenBootstrapped()) {
            $this->bootstrap();
        }

        // Run Application
        $this->run();
    }

    /**
     * Run Application
     *
     * @return void
     */
    public function run(): void
    {
        require_once $this->path('routes.php');
        $this->app['route']->run();
    }

    /**
     * Get the version number of the application.
     *
     * @return string
     */
    public function version(): string
    {
        return static::VERSION;
    }

    /**
     * Get the configs of the application.
     *
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Application root path
     *
     * @return string
     */
    public function root(): string
    {
        return $this->root;
    }

    /**
     * Application document root path
     *
     * @return string
     */
    public function docRoot(): string
    {
        return $this->docRoot;
    }

    /**
     * Application base folder
     *
     * @return string
     */
    public function baseFolder(): string
    {
        return $this->baseFolder;
    }

    /**
     * Get the application name.
     *
     * @return string
     */
    public function name(): string
    {
        return $this['config']->get('app.name');
    }

    /**
     * Get the application key.
     *
     * @return string
     */
    public function key(): string
    {
        $key = $this['config']->get('app.key');
        if (Str::startsWith($key, 'base64:')) {
            $key = substr($key, 7);
        }

        return $key;
    }

    /**
     * Get the current application locale.
     *
     * @return string
     */
    public function getLocale(): string
    {
        return $this['config']->get('app.locale');
    }

    /**
     * Set the current application locale.
     *
     * @param string $locale
     *
     * @return void
     */
    public function setLocale(string $locale): void
    {
        $this['config']->set('app.locale', $locale);

        $this['translator']->setLocale($locale);
    }

    /**
     * Determine if application locale is the given locale.
     *
     * @param string $locale
     *
     * @return bool
     */
    public function isLocale(string $locale): bool
    {
        return $this->getLocale() === $locale;
    }

    /**
     * Run the given array of bootstrap classes.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        $this->hasBeenBootstrapped = true;

        $this['events']->dispatch('bootstrapping', [$this]);
        $this->boot();
        $this['events']->dispatch('bootstrapped', [$this]);
    }

    /**
     * Register a callback to run before a bootstrapper.
     *
     * @param string   $bootstrapper
     * @param Closure $callback
     *
     * @return void
     */
    public function beforeBootstrapping(string $bootstrapper, Closure $callback): void
    {
        $this['events']->listen('bootstrapping: ' . $bootstrapper, $callback);
    }

    /**
     * Register a callback to run after a bootstrapper.
     *
     * @param string   $bootstrapper
     * @param Closure $callback
     *
     * @return void
     */
    public function afterBootstrapping(string $bootstrapper, Closure $callback): void
    {
        $this['events']->listen('bootstrapped: ' . $bootstrapper, $callback);
    }

    /**
     * Determine if the application has been bootstrapped before.
     *
     * @return bool
     */
    public function hasBeenBootstrapped(): bool
    {
        return $this->hasBeenBootstrapped;
    }

    /**
     * Set the base path for the application.
     *
     * @param string $basePath
     *
     * @return $this
     */
    public function setBasePath(string $basePath): Container
    {
        $this->basePath = rtrim($basePath, '\/');

        $this->bindPathsInContainer();

        return $this;
    }

    /**
     * Get the path to the application "app" directory.
     *
     * @param string $path Optionally, a path to append to the app path
     *
     * @return string
     */
    public function path(string $path = ''): string
    {
        return $this->root() . DIRECTORY_SEPARATOR . 'app' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Get the base path of the Nur Framework installation.
     *
     * @param string $path Optionally, a path to append to the base path
     *
     * @return string
     */
    public function basePath(string $path = ''): string
    {
        return $this->root() . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Get the path to the application configuration files.
     *
     * @param string $path Optionally, a path to append to the config path
     *
     * @return string
     */
    public function configPath(string $path = ''): string
    {
        return $this->root() . DIRECTORY_SEPARATOR . 'config' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Get the path to the database directory.
     *
     * @param string $path Optionally, a path to append to the database path
     *
     * @return string
     */
    public function databasePath(string $path = ''): string
    {
        return $this->root() . DIRECTORY_SEPARATOR . 'database' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Get the path to the language files.
     *
     * @return string
     */
    public function langPath(): string
    {
        return $this->path() . DIRECTORY_SEPARATOR . 'Langs';
    }

    /**
     * Get the path to the storage directory.
     *
     * @param string $path Optionally, a path to append to the storage path
     *
     * @return string
     */
    public function storagePath(string $path = ''): string
    {
        return $this->root() . DIRECTORY_SEPARATOR . 'storage' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Get the path to the cache directory.
     *
     * @param string $path Optionally, a path to append to the cache path
     *
     * @return string
     */
    public function cachePath(string $path = ''): string
    {
        return $this->storagePath('cache') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Get the path to the application public files.
     *
     * @param string $path Optionally, a path to append to the public path
     *
     * @return string
     */
    public function publicPath(string $path = ''): string
    {
        return $this->root() . DIRECTORY_SEPARATOR . 'public' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Determine if the application is running in the console.
     *
     * @return bool
     */
    public function runningInConsole(): bool
    {
        if (Env::get('APP_RUNNING_IN_CONSOLE') !== null) {
            return Env::get('APP_RUNNING_IN_CONSOLE') === true;
        }

        return php_sapi_name() === 'cli' || php_sapi_name() === 'phpdbg';
    }

    /**
     * Register all the configured providers.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function registerConfiguredProviders(): void
    {
        $providers = Collection::make($this->config['app.providers'])
            ->partition(function ($provider) {
                return Str::startsWith($provider, 'Nur\\');
            });
        $providers->splice(1, 0, [$this->make(PackageManifest::class)->providers()]);
        (new ProviderRepository($this, new Filesystem, $this->getCachedServicesPath()))
            ->load($providers->collapse()->toArray());
    }

    /**
     * Register a service provider with the application.
     *
     * @param ServiceProvider|string $provider
     * @param bool                   $force
     *
     * @return ServiceProvider
     */
    public function register($provider, bool $force = false)
    {
        if (($registered = $this->getProvider($provider)) && !$force) {
            return $registered;
        }

        // If the given "provider" is a string, we will resolve it, passing in the
        // application instance automatically for the developer. This is simply
        // a more convenient way of specifying your service provider classes.
        if (is_string($provider)) {
            $provider = $this->resolveProvider($provider);
        }

        if (method_exists($provider, 'register')) {
            $provider->register();
        }

        // If there are bindings / singletons set as properties on the provider we
        // will spin through them and register them with the application, which
        // serves as a convenience layer while registering a lot of bindings.
        if (property_exists($provider, 'bindings')) {
            foreach ($provider->bindings as $key => $value) {
                $this->bind($key, $value);
            }
        }

        if (property_exists($provider, 'singletons')) {
            foreach ($provider->singletons as $key => $value) {
                $this->singleton($key, $value);
            }
        }

        $this->markAsRegistered($provider);

        // If the application has already booted, we will call this boot method on
        // the provider class, so it has an opportunity to do its boot logic and
        // will be ready for any usage by this developer's application logic.
        if ($this->isBooted()) {
            $this->bootProvider($provider);
        }

        return $provider;
    }

    /**
     * Get the registered service provider instance if it exists.
     *
     * @param ServiceProvider|string $provider
     *
     * @return ServiceProvider|null
     */
    public function getProvider($provider): ?ServiceProvider
    {
        return array_values($this->getProviders($provider))[0] ?? null;
    }

    /**
     * Get the registered service provider instances if any exist.
     *
     * @param ServiceProvider|string $provider
     *
     * @return array
     */
    public function getProviders($provider): array
    {
        $name = is_string($provider) ? $provider : get_class($provider);

        return Arr::where($this->serviceProviders, function ($value) use ($name) {
            return $value instanceof $name;
        });
    }

    /**
     * Resolve a service provider instance from the class name.
     *
     * @param string $provider
     *
     * @return ServiceProvider
     */
    public function resolveProvider(string $provider): ServiceProvider
    {
        return new $provider($this);
    }

    /**
     * Load and boot all the remaining deferred providers.
     *
     * @return void
     */
    public function loadDeferredProviders(): void
    {
        // We will simply spin through each of the deferred providers and register each
        // one and boot them if the application has booted. This should make each of
        // the remaining services available to this application for immediate use.
        foreach ($this->deferredServices as $service => $provider) {
            $this->loadDeferredProvider($service);
        }

        $this->deferredServices = [];
    }

    /**
     * Load the provider for a deferred service.
     *
     * @param string $service
     *
     * @return void
     */
    public function loadDeferredProvider(string $service): void
    {
        if (! $this->isDeferredService($service)) {
            return;
        }

        $provider = $this->deferredServices[$service];

        // If the service provider has not already been loaded and registered we can
        // register it with the application and remove the service from this list
        // of deferred services, since it will already be loaded on subsequent.
        if (! isset($this->loadedProviders[$provider])) {
            $this->registerDeferredProvider($provider, $service);
        }
    }

    /**
     * Register a deferred provider and service.
     *
     * @param string      $provider
     * @param string|null $service
     *
     * @return void
     */
    public function registerDeferredProvider(string $provider, string $service = null): void
    {
        // Once the provider that provides the deferred service has been registered we
        // will remove it from our local list of the deferred services with related
        // providers so that this container does not try to resolve it out again.
        if ($service) {
            unset($this->deferredServices[$service]);
        }

        $this->register($instance = new $provider($this));

        if (! $this->isBooted()) {
            $this->booting(function () use ($instance) {
                $this->bootProvider($instance);
            });
        }
    }

    /**
     * Resolve the given type from the container.
     *
     * (Overriding Container::make)
     *
     * @param string $abstract
     * @param array  $parameters
     *
     * @return mixed
     * @throws BindingResolutionException
     */
    public function make($abstract, array $parameters = [])
    {
        $this->loadDeferredProviderIfNeeded($abstract = $this->getAlias($abstract));

        return parent::make($abstract, $parameters);
    }

    /**
     * Resolve the given type from the container.
     *
     * @param string $abstract
     * @param array  $parameters
     * @param bool   $raiseEvents
     *
     * @return mixed
     * @throws BindingResolutionException|CircularDependencyException
     */
    protected function resolve($abstract, $parameters = [], $raiseEvents = true)
    {
        $this->loadDeferredProviderIfNeeded($abstract = $this->getAlias($abstract));

        return parent::resolve($abstract, $parameters, $raiseEvents);
    }

    /**
     * Load the deferred provider if the given type is a deferred service and the instance has not been loaded.
     *
     * @param  string  $abstract
     * @return void
     */
    protected function loadDeferredProviderIfNeeded(string $abstract)
    {
        if ($this->isDeferredService($abstract) && ! isset($this->instances[$abstract])) {
            $this->loadDeferredProvider($abstract);
        }
    }

    /**
     * Determine if the given abstract type has been bound.
     *
     * (Overriding Container::bound)
     *
     * @param string $abstract
     *
     * @return bool
     */
    public function bound($abstract): bool
    {
        return $this->isDeferredService($abstract) || parent::bound($abstract);
    }

    /**
     * Determine if the application has booted.
     *
     * @return bool
     */
    public function isBooted(): bool
    {
        return $this->booted;
    }

    /**
     * Boot the application's service providers.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->isBooted()) {
            return;
        }

        // Once the application has booted we will also fire some "booted" callbacks
        // for any listeners that need to do work after this initial booting gets
        // finished. This is useful when ordering the boot-up processes we run.
        $this->fireAppCallbacks($this->bootingCallbacks);

        array_walk($this->serviceProviders, function ($p) {
            $this->bootProvider($p);
        });

        $this->booted = true;

        $this->fireAppCallbacks($this->bootedCallbacks);
    }

    /**
     * Register a new boot listener.
     *
     * @param mixed $callback
     *
     * @return void
     */
    public function booting($callback): void
    {
        $this->bootingCallbacks[] = $callback;
    }

    /**
     * Register a new "booted" listener.
     *
     * @param mixed $callback
     *
     * @return void
     */
    public function booted($callback): void
    {
        $this->bootedCallbacks[] = $callback;

        if ($this->isBooted()) {
            $this->fireAppCallbacks([$callback]);
        }
    }

    /**
     * Get the path to the cached services.php file.
     *
     * @return string
     */
    public function getCachedServicesPath(): string
    {
        return $this->cachePath() . '/services.php';
    }

    /**
     * Get the path to the cached packages.php file.
     *
     * @return string
     */
    public function getCachedPackagesPath(): string
    {
        return $this->cachePath() . '/packages.php';
    }

    /**
     * Determine if the application configuration is cached.
     *
     * @return bool
     */
    public function configurationIsCached(): bool
    {
        return $this['files']->exists($this->getCachedConfigPath());
    }

    /**
     * Get the path to the configuration cache file.
     *
     * @return string
     */
    public function getCachedConfigPath(): string
    {
        return $this->cachePath('config.php');
    }

    /**
     * Determine if the application routes are cached.
     *
     * @return bool
     */
    public function routesAreCached(): bool
    {
        return $this['files']->exists($this->getCachedRoutesPath());
    }

    /**
     * Get the path to the routes cache file.
     *
     * @return string
     */
    public function getCachedRoutesPath(): string
    {
        return $this->cachePath('routes.php');
    }

    /**
     * Determine if the application is currently down for maintenance.
     *
     * @return bool
     */
    public function isDownForMaintenance(): bool
    {
        return $this['files']->exists($this->storagePath('app.down'));
    }

    /**
     * Register a terminating callback with the application.
     *
     * @param Closure $callback
     *
     * @return $this
     */
    public function terminating(Closure $callback): Application
    {
        $this->terminatingCallbacks[] = $callback;

        return $this;
    }

    /**
     * Terminate the application.
     *
     * @return void
     */
    public function terminate(): void
    {
        foreach ($this->terminatingCallbacks as $terminating) {
            $this->call($terminating);
        }
    }

    /**
     * Get the service providers that have been loaded.
     *
     * @return array
     */
    public function getLoadedProviders(): array
    {
        return $this->loadedProviders;
    }

    /**
     * Get the application's deferred services.
     *
     * @return array
     */
    public function getDeferredServices(): array
    {
        return $this->deferredServices;
    }

    /**
     * Set the application's deferred services.
     *
     * @param array $services
     *
     * @return void
     */
    public function setDeferredServices(array $services): void
    {
        $this->deferredServices = $services;
    }

    /**
     * Add an array of services to the application's deferred services.
     *
     * @param array $services
     *
     * @return void
     */
    public function addDeferredServices(array $services): void
    {
        $this->deferredServices = array_merge($this->deferredServices, $services);
    }

    /**
     * Determine if the given service is a deferred service.
     *
     * @param string $service
     *
     * @return bool
     */
    public function isDeferredService(string $service): bool
    {
        return isset($this->deferredServices[$service]);
    }

    /**
     * Configure the real-time facade namespace.
     *
     * @param string $namespace
     *
     * @return void
     */
    public function provideFacades(string $namespace): void
    {
        AliasLoader::setFacadeNamespace($namespace);
    }

    /**
     * Register the core class aliases in the container.
     *
     * @return void
     */
    public function registerCoreContainerAliases(): void
    {
        // Prepare Facades
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication($this);

        foreach ($this->registerCoreAliases as $key => $alias) {
            $this->alias($key, $alias);
            if (!class_exists($key)) {
                class_alias($alias, $key);
            }
        }
    }

    /**
     * Flush the container of all bindings and resolved instances.
     *
     * @return void
     */
    public function flush(): void
    {
        parent::flush();

        $this->buildStack = [];
        $this->loadedProviders = [];
        $this->bootedCallbacks = [];
        $this->bootingCallbacks = [];
        $this->deferredServices = [];
        $this->reboundCallbacks = [];
        $this->serviceProviders = [];
        $this->resolvingCallbacks = [];
        $this->terminatingCallbacks = [];
        $this->afterResolvingCallbacks = [];
        $this->globalResolvingCallbacks = [];
    }

    /**
     * Get the application namespace.
     *
     * @return string
     *
     * @throws RuntimeException
     */
    public function getNamespace(): string
    {
        if (!is_null($this->namespace)) {
            return $this->namespace;
        }

        $composer = json_decode(file_get_contents($this->basePath('composer.json')), true);

        foreach ((array)data_get($composer, 'autoload.psr-4') as $namespace => $path) {
            foreach ((array)$path as $pathChoice) {
                if (realpath($this->path()) == realpath($this->basePath() . '/' . $pathChoice)) {
                    return $this->namespace = $namespace;
                }
            }
        }

        throw new RuntimeException('Unable to detect application namespace.');
    }

    /**
     * Determine if application is in local environment.
     *
     * @return bool
     */
    public function isLocal(): bool
    {
        return $this->config['app']['env'] === 'local';
    }

    /**
     * Determine if application is in production environment.
     *
     * @return bool
     */
    public function isProduction(): bool
    {
        return $this->config['app']['env'] === 'production';
    }

    /**
     * Get the path to the environment file directory.
     *
     * @return string
     */
    public function environmentPath(): string
    {
        return $this->environmentPath ?: $this->basePath;
    }

    /**
     * Get or check the current application environment.
     *
     * @param string|array $environments
     *
     * @return string|bool
     */
    public function environment(...$environments)
    {
        if (count($environments) > 0) {
            $patterns = is_array($environments[0]) ? $environments[0] : $environments;

            return Str::is($patterns, $this['env']);
        }

        return $this['env'];
    }

    /**
     * Run the given array of bootstrap classes.
     *
     * @param string[] $bootstrappers
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function bootstrapWith(array $bootstrappers): void
    {
        $this->hasBeenBootstrapped = true;

        foreach ($bootstrappers as $bootstrapper) {
            $this['events']->dispatch('bootstrapping: ' . $bootstrapper, [$this]);

            $this->make($bootstrapper)->bootstrap($this);

            $this['events']->dispatch('bootstrapped: ' . $bootstrapper, [$this]);
        }
    }

    /**
     * Detect the application's current environment.
     *
     * @param Closure $callback
     *
     * @return string
     */
    public function detectEnvironment(Closure $callback): string
    {
        $args = $_SERVER['argv'] ?? null;

        return $this['env'] = (new EnvironmentDetector)->detect($callback, $args);
    }

    /**
     * Get the environment file the application is using.
     *
     * @return string
     */
    public function environmentFile(): string
    {
        return $this->environmentFile ?: '.env';
    }

    /**
     * Get the fully qualified path to the environment file.
     *
     * @return string
     */
    public function environmentFilePath(): string
    {
        return $this->environmentPath() . DIRECTORY_SEPARATOR . $this->environmentFile();
    }

    /**
     * Set the environment file to be loaded during bootstrapping.
     *
     * @param string $file
     *
     * @return $this
     */
    public function loadEnvironmentFrom(string $file): Application
    {
        $this->environmentFile = $file;

        return $this;
    }

    /**
     * Application Initializer
     *
     * @return void
     */
    protected function init(): void
    {
        $this->registerCoreProviders = [
            \Nur\Providers\Event::class,
            \Nur\Providers\Request::class,
            \Nur\Providers\Response::class,
            \Nur\Providers\Session::class,
            \Nur\Providers\Cookie::class,
            \Nur\Providers\Route::class,
            \Nur\Providers\Uri::class,
            \Nur\Providers\Load::class,
            \Nur\Providers\Date::class,
            \Nur\Providers\Encryption::class,
            \Nur\Providers\Log::class,
        ];

        $this->registerCoreAliases = [
            'Request'   => \Nur\Facades\Request::class,
            'Response'  => \Nur\Facades\Response::class,
            'Cookie'    => \Nur\Facades\Cookie::class,
            'Session'   => \Nur\Facades\Session::class,
            'Route'     => \Nur\Facades\Route::class,
            'Uri'       => \Nur\Facades\Uri::class,
            'Date'      => \Nur\Facades\Date::class,
            'Log'       => \Nur\Facades\Log::class,
            'Validation'=> \Nur\Facades\Validation::class,
            'File'      => \Nur\Facades\File::class,
        ];
    }

    /**
     * Load application configuration files
     *
     * @return void
     * @throws
     */
    protected function loadConfigFiles(): void
    {
        try {
            if (file_exists($this->cachePath('config.php'))) {
                $this->config = require_once $this->cachePath('config.php');
            } else {
                $env = Dotenv::createImmutable($this->root);
                $env->load();
                foreach (glob($this->root . '/config/*.php') as $file) {
                    $keyName = strtolower(str_replace('.php', '', basename($file)));
                    $this->config[$keyName] = require_once $file;
                }
            }
        } catch (Exception $e) {
            die(printf(
                "Configuration information could not be retrieved properly.\nError Message: %s",
                $e->getMessage()
            ));
        }
    }

    /**
     * Register the basic bindings into the container.
     *
     * @return void
     */
    protected function registerBaseBindings(): void
    {
        static::setInstance($this);

        $this->instance('app', $this);

        $this->instance(Container::class, $this);

        $this->loadConfigFiles();
        $this->singleton('config', function () {
            return new \Nur\Config\Config($this->config);
        });

        $this->singleton('files', function () {
            return new Filesystem;
        });

        $this->instance(PackageManifest::class, new PackageManifest(
            new Filesystem, $this->basePath(), $this->getCachedPackagesPath()
        ));
    }

    /**
     * Register all the base service providers.
     *
     * @return void
     */
    protected function registerBaseServiceProviders(): void
    {
        foreach ($this->registerCoreProviders as $provider) {
            $this->register(new $provider($this));
        }
    }

    /**
     * Register providers of Application
     *
     * @return void
     */
    protected function registerApplicationProviders(): void
    {
        foreach ($this->config['app']['providers'] as $provider) {
            $this->register(new $provider($this));
        }
    }

    /**
     * Register aliases of Application
     *
     * @return void
     */
    protected function registerApplicationAliases(): void
    {
        foreach ($this->config['app']['aliases'] as $key => $alias) {
            $this->alias($key, $alias);
            if (!class_exists($key)) {
                class_alias($alias, $key);
            }
        }
    }

    /**
     * Bind all the application paths in the container.
     *
     * @return void
     */
    protected function bindPathsInContainer(): void
    {
        $this->instance('path', $this->path());
        $this->instance('path.base', $this->basePath());
        $this->instance('path.lang', $this->langPath());
        $this->instance('path.config', $this->configPath());
        $this->instance('path.storage', $this->storagePath());
        $this->instance('path.database', $this->databasePath());
        $this->instance('path.cache', $this->cachePath());
        $this->instance('path.public', $this->publicPath());
    }

    /**
     * Mark the given provider as registered.
     *
     * @param ServiceProvider $provider
     *
     * @return void
     */
    protected function markAsRegistered(ServiceProvider $provider): void
    {
        $this->serviceProviders[] = $provider;

        $this->loadedProviders[get_class($provider)] = true;
    }

    /**
     * Boot the given service provider.
     *
     * @param ServiceProvider $provider
     *
     * @return mixed|void
     */
    protected function bootProvider(ServiceProvider $provider)
    {
        if (method_exists($provider, 'boot')) {
            return $this->call([$provider, 'boot']);
        }
    }

    /**
     * Call the booting callbacks for the application.
     *
     * @param array $callbacks
     *
     * @return void
     */
    protected function fireAppCallbacks(array $callbacks): void
    {
        foreach ($callbacks as $callback) {
            $callback($this);
        }
    }

    /**
     * Whoops Initializer
     *
     * @return void
     */
    protected function initWhoops(): void
    {
        $whoops = new WhoopsRun;
        if (request()->headers->get('content-type') === 'application/json') {
            $whoops->prependHandler(new \Whoops\Handler\JsonResponseHandler);
        } else {
            $whoops->prependHandler(new \Whoops\Handler\PrettyPageHandler);
        }
        $whoops->register();
    }
}
