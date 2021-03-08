<?php
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

chdir(dirname(__DIR__ ));

require __DIR__ . '/../vendor/autoload.php';


define('DS', DIRECTORY_SEPARATOR);
define('ROOT_DIR', realpath(__DIR__ . '/..') );


// Create Container using PHP-DI
$container = new Container();

// Set container to create App with on AppFactory
AppFactory::setContainer($container);

$app = AppFactory::create();

$container->set('config', function () {
    return new \App\Services\Config();
});




$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world! " . __DIR__);
    return $response;
});

$app->get('/help', \App\Controllers\HelpController::class);

$app->run();
