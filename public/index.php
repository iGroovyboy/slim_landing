<?php

use App\Services\Config;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/boot.php' ;

session_start();

$app->get('/', \App\Controllers\HomeController::class);

$app->get('/help', \App\Controllers\HelpController::class);

$app->get('/about', \App\Controllers\AboutController::class);

// ADMIN Endpoints
$_SESSION['auth'] = false;

$app->map(['GET', 'POST'], '/login', \App\Controllers\AuthController::class . ':login');

$app->group('/admin', function (RouteCollectorProxy $group) {

    $group->get('', \App\Controllers\AdminController::class)->add(new \App\Middleware\AuthMiddleware());

    $group->get('/logout', \App\Controllers\AuthController::class . ':logout');

})->add(new \App\Middleware\AuthMiddleware());

// API Endpoints
//$app->group('/api', function (RouteCollectorProxy $group) {
//    $group->map(['GET', 'DELETE', 'PATCH', 'PUT'], '', function ($request, $response, array $args) {
//        return $response;
//    })->setName('user');
//
//    $group->get('/login', \App\Controllers\AuthController::class . ':login');
//    $group->get('/logout', \App\Controllers\AuthController::class . ':logout');
//});

$app->run();
