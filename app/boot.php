<?php

use App\Services\Config;
use App\Services\DebugBar;
use App\Services\DebugSlimCollector;
use App\Services\Install;
use App\Services\Log;
use DI\ContainerBuilder as ContainerBuilder;
use DI\Bridge\Slim\Bridge as SlimlandAppFactory;
//use Slim\Factory\AppFactory as SlimlandAppFactory;
use Symfony\Component\PropertyAccess\PropertyAccess as PropertyAccessor;


DebugBar::boot(\DebugBar\StandardDebugBar::class);
DebugBar::get("messages")->addMessage("Started boot");


define('ROOT_DIR', realpath(__DIR__ . '/..'));
define('DS', DIRECTORY_SEPARATOR);

// Setup error handling for prod
ini_set('display_errors', 0);
ini_set("log_errors", 1);
ini_set('error_log', ROOT_DIR . DS . 'errors.log');
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

Log::boot(\Slim\Logger::class);

// Container/DI setup ----------------------------------------------------------------------
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . DS . 'container.php');

// if prod
// $containerBuilder->enableCompilation(__DIR__ . '/tmp');
// $containerBuilder->writeProxiesToFile(true, __DIR__ . '/tmp/proxies');

// if weird errors
// $containerBuilder->ignorePhpDocErrors(true);
// $containerBuilder->useAnnotations(false)

$container = $containerBuilder->build();

//SlimlandAppFactory::setContainer($container); // native Slim

// Create app
$app = SlimlandAppFactory::create($container);





// Container/DI setup OVER ----------------------------------------------------------------------

DebugBar::get()->addCollector(new DebugSlimCollector($app));

// Load Configs
Config::setPropertyAccessor(
    PropertyAccessor::createPropertyAccessorBuilder()
        ->enableExceptionOnInvalidIndex()
        ->getPropertyAccessor()
);
Config::loadAll();
Config::set( 'app/paths/root', ROOT_DIR );

// Engage Whoops error handler
if (Config::get('app/debug')) {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();

    ini_set('display_errors', 1);
}

// Load DB Config if any
try {
    \App\Services\DB\DB::setDriver(Config::get('db/driver'));
    \App\Services\DB\DB::connect(Config::get('db'));
} catch (Symfony\Component\PropertyAccess\Exception\NoSuchIndexException $e) {
    Log::info('No DB config found! Will redirect to Installation page');
}

// Install available themes so that install or default theme would run
Install::publicThemes();

DebugBar::get("messages")->addMessage("Finished boot");
