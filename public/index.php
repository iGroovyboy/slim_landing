<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/boot.php';

session_start();

$app->addBodyParsingMiddleware(); // allow POST method in ajax

require __DIR__ . '/../app/routes.php';

$app->run();
