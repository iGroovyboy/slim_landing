<?php

use App\Models\Option;
use App\Models\User;
use App\Services\Config\Config;
use App\Services\Config\Env;
use App\Services\DB\DB;
use App\Services\Storage;
use Slim\Routing\RouteCollectorProxy as RouteGroup;

$app->add(new \App\Middleware\TrailingSlashMiddleware());

/*
 *
 * BASIC PUBLIC ROUTES
 *
 */
$app->map(['GET', 'POST'], '/', \App\Controllers\HomeController::class)->setName('home');
$app->get('/help', \App\Controllers\HelpController::class);
$app->get('/about', \App\Controllers\AboutController::class);

$app->map(['GET', 'POST'], '/login', \App\Controllers\AuthController::class . ':login')->setName('login');
$app->get('/logout', \App\Controllers\AuthController::class . ':logout')->setName('logout');

/*
 *
 * INSTALLATION ROUTES
 * TODO refactor using middleware
 */
try {
    Env::has('db/driver');
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
$_SESSION['isLoggedIn'] = true; // dev only; remove on prod

$app->get('/edit', \App\Controllers\EditController::class)->setName('x-edit')->add(new \App\Middleware\AuthMiddleware());

$app->group(
    '/admin',
    function (RouteGroup $group) {
        $group->get('', \App\Controllers\AdminController::class)->setName('dashboard');
    }
)->add(new \App\Middleware\AuthMiddleware());

/*
 *
 * API ENDPOINTS
 *
 */
$app->group(
    '/api',
    function (RouteGroup $group) {
        $group->map(
            ['GET', 'POST', 'DELETE', 'PATCH', 'PUT'],
            '/nodes/{key}',
            \App\Controllers\Api\NodesController::class
        )->setName('nodes');

        $group->get(
            '/uploads/allowed',
            function ($request, $response) {
                $json = json_encode(
                    Storage::getExtensionsMimes(
                        Option::get('allowedFileExtensions')));

                $response->getBody()->write($json);

                return $response->withHeader('Content-Type', 'application/json');
            }
        )->setName('uploads.allowed');

        /*
         * 404 api pattern
         */
        $group->any('/{uri:.*}', \App\Controllers\NotFoundController::class . ':api')->setName('api_404');
    }
); // TODO add auth middleware


/*
 *
 * 404 pattern
 *
 */
$app->any('/{uri:.*}', \App\Controllers\NotFoundController::class)->setName('404');
