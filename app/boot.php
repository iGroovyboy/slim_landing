<?php

use App\Services\Config;
use DI\Container;
use Slim\Factory\AppFactory;

define('DS', DIRECTORY_SEPARATOR);
define('ROOT_DIR', realpath(__DIR__ . '/..') );
define('THEMES_DIR',  realpath(ROOT_DIR . '/themes') );

// Container setup
$container = new Container();

AppFactory::setContainer($container);

$app = AppFactory::create();
//
//$container->set('config', function () {
//    return new \App\Services\Config();
//});

Config::use('app.json');

// TODO get from config
\App\Services\DB\DB::setDriver('sqlite');
