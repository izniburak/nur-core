<?php
/**
* nur - a simple framework for PHP Developers
*
* @author   izni burak demirtaş (@izniburak) <izniburak@gmail.com>
* @web      <http://burakdemirtas.org>
* @url      <https://github.com/izniburak/nur>
* @license  The MIT License (MIT) - <http://opensource.org/licenses/MIT>
*/

use Nur\Http\Http;
use Nur\Http\Request;
use Nur\Http\Response;
use Nur\Http\Session;
use Nur\Load\AutoLoad;
use Nur\Uri\Uri;
use Nur\Exception\ExceptionHandler;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

ob_start();
session_start();

define('NUR_VERSION', '1.2.1');
define('ROOT', realpath(getcwd()));
define('DOC_ROOT', realpath(http::server('DOCUMENT_ROOT')));
define('BASE_FOLDER', trim(str_replace('\\', '/', str_replace(DOC_ROOT, '', ROOT) . '/'), '/'));

global $config;

try {
    $config = Yaml::parse(file_get_contents(ROOT . '/app/config.yml'));
}
catch (ParseException $e) {
    die(printf("<b>Unable to parse the Config YAML string:</b><br />Error Message: %s", $e->getMessage()));
}

define('ADMIN_FOLDER', trim($config['admin'], '/'));
define('ASSETS_FOLDER', trim($config['assets'], '/'));
define('APP_MODE', strtolower($config['mode']));
define('IP_ADDRESS', Http::getClientIP());
define('APP_KEY', $config['key']);

switch (APP_MODE)
{
    case 'dev':
        ini_set('display_errors', 1); break;
    case 'test':
    case 'prod':
        ini_set('display_errors', 0);
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        break;
    default:
        header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
        die('The application environment is not set correctly.');
}

date_default_timezone_set($config['timezone']);

if(!Session::hasKey('_token'))
    Session::set('_token', sha1(uniqid(mt_rand() . $config['salt'], true)) );

define('_TOKEN', Session::get('_token'));

require_once 'helper.php';
require_once 'Router/Route.php';

Uri::getInstance();
Response::getInstance();
Request::getInstance();
AutoLoad::getInstance();

if($routerFiltersFile = realpath(ROOT . '/app/filters.php'))
    require_once $routerFiltersFile;
require_once realpath(ROOT . '/app/routes.php');
Route::run();

if(ob_get_contents())
    ob_end_flush();
