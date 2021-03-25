<?php

use App\Services\Config;
use DI\Container;
use Slim\Factory\AppFactory;
use Symfony\Component\PropertyAccess\PropertyAccess;

define('ROOT_DIR', realpath(__DIR__ . '/..'));
define('DS', DIRECTORY_SEPARATOR);

// Container setup
$container = new Container();

AppFactory::setContainer($container);

$app = AppFactory::create();

Config::setPropertyAccessor(
    PropertyAccess::createPropertyAccessorBuilder()
        ->enableExceptionOnInvalidIndex()
        ->getPropertyAccessor()
);
Config::loadAll();
Config::set( 'app/paths/root', ROOT_DIR );

try {
    \App\Services\DB\DB::setDriver(Config::get('db/driver'));
    \App\Services\DB\DB::start(Config::get('db'));
} catch (Symfony\Component\PropertyAccess\Exception\NoSuchIndexException $e) {
    //dump($e);
}

