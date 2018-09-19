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

$app = new Kernel();

define('NUR_VERSION', Kernel::VERSION);
define('ROOT', $app->root());
define('DOC_ROOT', $app->docRoot());
define('BASE_FOLDER', $app->baseFolder());
define('APP_ENV', strtolower(config('app.env')));
define('ADMIN_FOLDER', trim(config('app.admin'), '/'));
define('ASSETS_FOLDER', trim(config('app.assets'), '/'));
date_default_timezone_set(config('app.timezone'));

$app->start($env = APP_ENV);
