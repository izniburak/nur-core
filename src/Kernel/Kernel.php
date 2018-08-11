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
use Nur\Kernel\Facade;
use Nur\Container\Container;
use Nur\Facades\Http;
use Whoops\Run as WhoopsRun;
use Whoops\Handler\PrettyPageHandler as WhoopsPrettyPageHandler;

class Kernel
{
    /**
     * Nur Framework Version
     * 
     * @var string
     */
    const VERSION		= '1.4.1';

    /**
     * Framework container 
     * 
     * @var Nur\Container\Container|null
     */
    private $app		= null;

    /**
     * Framework config 
     * 
     * @var array|null
     */
    private $config		= null;

    /**
     * Framework root folder 
     * 
     * @var string|null
     */
    private $root		= null;

    /**
     * Framework document root folder 
     * 
     * @var string|null
     */
    private $docRoot	= null;

    /**
     * Class constructer
     *
     * @return void
     */
    public function __construct()
    {   
        $this->app = Container::getInstance();
        $this->root	= realpath(getcwd());
        try {
            foreach(glob($this->root . '/config/*.php') as $file) {
                $keyName = strtolower(str_replace(
                    [$this->root . '/config/', '.php'],
                    '', 
                    $file
                ));
                $this->config[$keyName] = require($file);
            }
        }
        catch (Exception $e) {
            die(printf(
                "The configuration information could not be retrieved properly. \n Error Message: %s",
                $e->getMessage()
            ));
        }
        
        $this->init();
        $this->docRoot		= realpath($this->app->get('http')->server('DOCUMENT_ROOT'));
        $this->baseFolder	= trim(
            str_replace('\\', '/', str_replace($this->docRoot, '', $this->root	) . '/'), '/'
        );
    }
    
    /**
     * Kernel start
     *
     * @param string $env
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
                header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
                die('The application environment is not set correctly.');
        }
        
        if ($routerFiltersFile = realpath($this->root . '/app/filters.php')) {
            require_once $routerFiltersFile;
        }
        require_once realpath($this->root . '/app/routes.php');
        $this->app->get('route')->run();
    }
    
    /**
     * Generate Application token
     *
     * @return string
     */
    public function generateToken()
    {
        $session = $this->app->get('session');
        if (! $session->hasKey('_nur_token')) {
            $session->set('_nur_token', sha1(uniqid(mt_rand(), true)) );
        }

        return $session->get('_nur_token');
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
     * Application Initializer
     *
     * @return void
     */
    protected function init()
    {
        $this->app->set('config', $this->config);

        $this->registerCoreProviders();
        $this->registerCoreAliases();
        $this->registerApplicationProviders(config('services.providers'));
        $this->resolveFacades(config('services.aliases'));
    }

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

    protected function registerApplicationProviders($providers)
    {
        foreach ($providers as $provider) {
            (new $provider($this->app))->register();
        }
    }

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

    protected function resolveFacades($aliases)
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
}
