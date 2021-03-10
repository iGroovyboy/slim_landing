<?php

use App\Services\Config;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/boot.php' ;

$app->get('/', \App\Controllers\HomeController::class);

$app->get('/help', \App\Controllers\HelpController::class);

$app->get('/about', \App\Controllers\AboutController::class);

$app->run();
