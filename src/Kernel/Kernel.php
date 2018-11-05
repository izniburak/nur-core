<?php
/**
 * nur - a simple framework for PHP Developers
 *
 * @author   izni burak demirtaş (@izniburak) <izniburak@gmail.com>
 * @web      <http://burakdemirtas.org>
 * @url      <https://github.com/izniburak/nur>
 * @license  The MIT License (MIT) - <http://opensource.org/licenses/MIT>
 */

namespace Nur\Kernel;

use Exception;
use Nur\Container\Container;
use Whoops\Handler\PrettyPageHandler as WhoopsPrettyPageHandler;
use Whoops\Run as WhoopsRun;

class Kernel
{
    /**
     * Nur Framework Version
     *
     * @var string
     */
    const VERSION = '1.0.1';

    /**
     * Framework container
     *
     * @var Nur\Container\Container|null
     */
    private $app = null;

    /**
     * Framework config
     *
     * @var array|null
     */
    private $config = null;

    /**
     * Framework root folder
     *
     * @var string|null
     */
    private $root = null;

    /**
     * Framework document root folder
     *
     * @var string|null
     */
    private $docRoot = null;

    /**
     * Framework root folder
     *
     * @var string|null
     */
    private $baseFolder = null;

    /**
     * Class constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->app = Container::getInstance();
        $this->root = realpath(getcwd());
        try {
            $dotenv = new \Dotenv\Dotenv($this->root);
            $dotenv->load();

            foreach (glob($this->root . '/config/*.php') as $file) {
                $keyName = strtolower(str_replace(
                    [$this->root . '/config/', '.php'],
                    '',
                    $file
                ));
                $this->config[$keyName] = require $file;
            }
        } catch (Exception $e) {
            die(printf(
                "The configuration information could not be retrieved properly.\nError Message: %s",
                $e->getMessage()
            ));
        }

        $this->init();
        $this->docRoot = realpath($this->app->get('http')->server('DOCUMENT_ROOT'));
        $this->baseFolder = trim(
            str_replace(
                '\\', '/', str_replace($this->docRoot, '', $this->root) . '/'
            ),
            '/'
        );
    }

    /**
     * Kernel start
     *
     * @param string $env
     *
     * @return void
     */
    public function start($env)
    {
        switch ($env) {
            case 'dev':
                ini_set('display_errors', 1);
                error_reporting(1);
                $this->initWhoops();
                break;
            case 'test':
            case 'prod':
                ini_set('display_errors', 0);
                error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
                break;
            default:
                header('HTTP/1.1 503 Service Unavailable.', true, 503);
                die('The application environment is not set correctly.');
        }

        require realpath($this->root . '/app/routes.php');
        $this->app->get('route')->run();
    }

    /**
     * Nur Framework version
     *
     * @return string
     */
    public function version()
    {
        return self::VERSION;
    }

    /**
     * Application root path
     *
     * @return string
     */
    public function root()
    {
        return $this->root;
    }

    /**
     * Application document root path
     *
     * @return string
     */
    public function docRoot()
    {
        return $this->docRoot;
    }

    /**
     * Application base folder path
     *
     * @return string
     */
    public function baseFolder()
    {
        return $this->baseFolder;
    }

    /**
     * Class destruct
     *
     * @return void
     */
    public function __destruct()
    {
        if (ob_get_contents()) {
            ob_end_flush();
        }
    }

    /**
     * Get the path to the application "app" directory.
     *
     * @param  string $path Optionally, a path to append to the app path
     *
     * @return string
     */
    public function path($path = '')
    {
        return $this->root() . DIRECTORY_SEPARATOR . 'app' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Get the base path of the Nur installation.
     *
     * @param  string $path Optionally, a path to append to the base path
     *
     * @return string
     */
    public function basePath($path = '')
    {
        return $this->root() . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Get the path to the application configuration files.
     *
     * @param  string $path Optionally, a path to append to the config path
     *
     * @return string
     */
    public function configPath($path = '')
    {
        return $this->root() . DIRECTORY_SEPARATOR . 'config' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Get the path to the database directory.
     *
     * @param  string $path Optionally, a path to append to the database path
     *
     * @return string
     */
    public function databasePath($path = '')
    {
        return $this->storagePath('database') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Get the path to the cache directory.
     *
     * @param  string $path Optionally, a path to append to the cache path
     *
     * @return string
     */
    public function cachePath($path = '')
    {
        return $this->storagePath('cache') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Get the path to the public / web directory.
     *
     * @return string
     */
    public function publicPath()
    {
        return $this->root() . DIRECTORY_SEPARATOR . 'public';
    }

    /**
     * Get the path to the storage directory.
     *
     * @param  string $path Optionally, a path to append to the storage path
     *
     * @return string
     */
    public function storagePath($path = '')
    {
        return $this->root() . DIRECTORY_SEPARATOR . 'storage' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * Application Initializer
     *
     * @return void
     * @throws
     */
    protected function init()
    {
        $this->app->set('config', function() {
            return new \Nur\Config\Config($this->config);
        });

        $this->bindPathsInContainer();
        $this->registerCoreProviders();
        $this->registerCoreAliases();
        $this->registerApplicationProviders(config('services.providers'));
        $this->resolveFacades(config('services.aliases'));
    }

    /**
     * Register providers of Framework core
     *
     * @return void
     */
    protected function registerCoreProviders()
    {
        foreach ([
                     \Nur\Providers\Load::class,
                     \Nur\Providers\Uri::class,
                     \Nur\Providers\Http::class,
                     \Nur\Providers\Route::class,
                     \Nur\Providers\Log::class,
                 ] as $provider) {
            (new $provider($this->app))->register();
        }
    }

    /**
     * Register aliases of Framework core
     *
     * @return void
     */
    protected function registerCoreAliases()
    {
        foreach ([
                     'Route' => \Nur\Facades\Route::class,
                 ] as $key => $value) {
            if (! class_exists($key)) {
                class_alias($value, $key);
            }
        }
    }

    /**
     * Register providers of Application
     *
     * @param $providers
     *
     * @return void
     */
    protected function registerApplicationProviders($providers)
    {
        foreach ($providers as $provider) {
            (new $provider($this->app))->register();
        }
    }

    /**
     * Resolve Facades
     *
     * @param array $aliases
     *
     * @return void
     */
    protected function resolveFacades(array $aliases)
    {
        // Prepare Facades
        Facade::clearResolvedInstances();
        Facade::setApplication($this->app);

        // Create Aliases
        foreach ($aliases as $key => $value) {
            if (! class_exists($key)) {
                class_alias($value, $key);
            }
        }
    }

    /**
     * Whoops Initializer
     *
     * @return void
     */
    protected function initWhoops()
    {
        $whoops = new WhoopsRun;
        $whoops->pushHandler(new WhoopsPrettyPageHandler);
        $whoops->register();
    }

    /**
     * Bind all of the application paths in the container.
     *
     * @return void
     * @throws
     */
    protected function bindPathsInContainer()
    {
        $this->app->set('path', $this->path());
        $this->app->set('path.base', $this->basePath());
        $this->app->set('path.public', $this->publicPath());
        $this->app->set('path.config', $this->configPath());
        $this->app->set('path.storage', $this->storagePath());
        $this->app->set('path.database', $this->databasePath());
        $this->app->set('path.cache', $this->cachePath());
    }
}
