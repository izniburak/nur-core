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

use Nur\Kernel\Facade;
use Nur\Router\Route;
use Nur\Facades\Http;
use Nur\Facades\Session;
use Nur\Load\AutoLoad;
use Nur\Load\Load;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use Whoops\Run as WhoopsRun;
use Whoops\Handler\PrettyPageHandler as WhoopsPrettyPageHandler;

class Kernel
{
    /**
     * Nur Framework Version
     * 
     * @var string
     */
    const VERSION		= '1.3.0';

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
        $this->root	= realpath(getcwd());
        try {
            $this->config = Yaml::parse(file_get_contents($this->root . '/app/config.yml'));
        }
        catch (ParseException $e) {
            die(printf("<b>Unable to parse the Config YAML string:</b><br />Error Message: %s", $e->getMessage()));
        }
        
        $this->init();
        $this->docRoot		= realpath(Http::server('DOCUMENT_ROOT'));
        $this->baseFolder	= trim(
            str_replace('\\', '/', str_replace($this->docRoot, '', $this->root	) . '/'), '/'
        );
    }
    
    /**
     * Kernel start
     *
     * @param Nur\Router\Route $route 
     * @param string $env
     * @return void
     */
    public function start(Route $route, $env)
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
        
        $this->autoLoader();
        if($routerFiltersFile = realpath($this->root . '/app/filters.php')) {
            require_once $routerFiltersFile;
        }
        require_once realpath($this->root . '/app/routes.php');
        $route->run();
    }

    /**
     * Application config 
     *
     * @return array
     */
    public function config()
    {
        return $this->config;
    }
    
    /**
     * Generate Application token
     *
     * @return string
     */
    public function generateToken()
    {
        if(!Session::hasKey('_nur_token')) {
            Session::set('_nur_token', sha1(uniqid(mt_rand() . config('salt'), true)) );
        }

        return Session::get('_nur_token');
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
     * Autoload class
     *
     * @return Nur\Load\Autoload
     */
    private function autoLoader()
    {
        return (new AutoLoad(new Load));
    }

    /**
     * Whoops Initializer
     *
     * @return void
     */
    private function initWhoops()
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
    private function init()
    {
        $app = $this->config();

        // Prepare Facades
        Facade::clearResolvedInstances();
        Facade::setApplication($app);
        
        // Create Aliases
        foreach ($app['services'] as $key => $value) {
            if(!class_exists($key)) {
                class_alias($value[1], $key);
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
        if(ob_get_contents()) {
            ob_end_flush();
        }
    }
}
