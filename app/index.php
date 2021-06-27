<?php

require_once __DIR__ . '/configuration.php';
require_once __DIR__ . '/middlewares/Logger.php';
require_once __DIR__ . '/middlewares/LogIn.php';

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->safeLoad();

// Instantiate App
$basePath = str_replace( '/index.php', '', $_SERVER['PHP_SELF'] );
$app = AppFactory::create();
$app->setBasePath( $basePath );

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

// Rutteo:
require_once __DIR__ . '/routes/autoRoute.php';

$app->run();