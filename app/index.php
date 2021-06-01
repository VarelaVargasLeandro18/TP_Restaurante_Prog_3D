<?php
error_reporting(-1);
ini_set('display_errors', -1);
date_default_timezone_set( 'America/Argentina/Buenos_Aires' );

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

// Instantiate App
$basePath = str_replace( '/index.php', '', $_SERVER['PHP_SELF'] );
$app = AppFactory::create();
$app->setBasePath( $basePath );

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

/*require_once './routes/EmpleadoRoute.php';
require_once './routes/ProductoRoute.php';
require_once './routes/MesaRoute.php';
require_once './routes/PedidoRoute.php';
require_once './routes/AuthRoute.php';*/

$app->run();