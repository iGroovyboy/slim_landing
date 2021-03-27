<?php

use App\Services\Config;
use App\Services\Install;
use DI\Container;
use Slim\Factory\AppFactory;
use Symfony\Component\PropertyAccess\PropertyAccess;

define('ROOT_DIR', realpath(__DIR__ . '/..'));
define('DS', DIRECTORY_SEPARATOR);

ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

// Container setup
$container = new Container();
AppFactory::setContainer($container);

// Create app
$app = AppFactory::create();

// Load Configs
Config::setPropertyAccessor(
    PropertyAccess::createPropertyAccessorBuilder()
        ->enableExceptionOnInvalidIndex()
        ->getPropertyAccessor()
);
Config::loadAll();
Config::set( 'app/paths/root', ROOT_DIR );

// Load DB Config if any
try {
    \App\Services\DB\DB::setDriver(Config::get('db/driver'));
    \App\Services\DB\DB::start(Config::get('db'));
} catch (Symfony\Component\PropertyAccess\Exception\NoSuchIndexException $e) {
    //dump($e);
}

// Install available themes so that install or default theme would run
Install::publicThemes();
