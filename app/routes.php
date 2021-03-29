<?php

use App\Models\User;
use App\Services\Config;
use App\Services\DB\DB;
use Slim\Routing\RouteCollectorProxy as RouteGroup;

/*
 *
 * BASIC PUBLIC ROUTES
 *
 */
$app->map(['GET', 'POST'], '/', \App\Controllers\HomeController::class)->setName('home');
$app->get('/help', \App\Controllers\HelpController::class);
$app->get('/about', \App\Controllers\AboutController::class);

$app->map(['GET', 'POST'], '/login', \App\Controllers\AuthController::class . ':login')->setName('login');


/*
 *
 * INSTALLATION ROUTES
 * TODO refactor using middleware
 */
try {
    Config::has('db/driver');
} catch (\Symfony\Component\PropertyAccess\Exception\NoSuchIndexException $e) {
    $app->post('/api/install_db', \App\Controllers\InstallController::class . ':setupDBConnection')->setName('install_db');
}

if ( ! DB::isConnected() || ! User::hasAny()) {
    $app->post('/api/install_admin', \App\Controllers\InstallController::class . ':setupAdminCredentials')->setName('install_admin');
}


/*
 *
 * ADMIN
 *
 */
//$_SESSION['isLoggedIn'] = true;


$app->group(
    '/admin',
    function (RouteGroup $group) {
        $group->get('', \App\Controllers\AdminController::class)->setName('dashboard');

        $group->get('/logout', \App\Controllers\AuthController::class . ':logout')->setName('logout');
    }
)->add(new \App\Middleware\AuthMiddleware());

/*
 *
 * API ENDPOINTS
 *
 */
//$app->group('/api', function (RouteCollectorProxy $group) {
//    $group->map(['GET', 'DELETE', 'PATCH', 'PUT'], '', function ($request, $response, array $args) {
//        return $response;
//    })->setName('user');
//
//    $group->get('/login', \App\Controllers\AuthController::class . ':login');
//    $group->get('/logout', \App\Controllers\AuthController::class . ':logout');
//        $group->any('/{uri:.*}', \App\Controllers\NotFoundController::class . ':api')->setName('api_404');;
//});

$app->any('/{uri:.*}', \App\Controllers\NotFoundController::class)->setName('404');
