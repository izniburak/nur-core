<?php
/**
* nur - a simple framework for PHP Developers
*
* @author   izni burak demirtaş (@izniburak) <izniburak@gmail.com>
* @web      <http://burakdemirtas.org>
* @url      <https://github.com/izniburak/nur>
* @license  The MIT License (MIT) - <http://opensource.org/licenses/MIT>
*/

ob_start();
session_start();

use Nur\Kernel\Kernel;
use Nur\Facades\Http;
use Nur\Router\Route;

$app = new Kernel();
$config = $app->config();

define('NUR_VERSION', Kernel::VERSION);
define('DS', '/');
define('ROOT', $app->root());
define('DOC_ROOT', $app->docRoot());
define('BASE_FOLDER', $app->baseFolder());
define('ADMIN_FOLDER', trim($config['admin'], '/'));
define('ASSETS_FOLDER', trim($config['assets'], '/'));
define('APP_ENV', strtolower($config['env']));
define('IP_ADDRESS', Http::getClientIP());
define('APP_KEY', $config['key']);
define('_TOKEN', $app->generateToken());
date_default_timezone_set($config['timezone']);

$app->start(new Route(), $env = APP_ENV);
