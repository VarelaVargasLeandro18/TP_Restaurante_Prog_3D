<?php
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
// require_once './middlewares/Logger.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();
$app->setBasePath('/TP_Restaurante/slim-php-mysql-heroku/app');

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

$app->get('/', function( Request $req, Response $rep ) {
    $rep->getBody()->write("AAA");
    return $rep;
});

//require_once './routes/PermisoEmpleadoSectorRoute.php';
require_once './routes/EmpleadoRoute.php';
require_once './routes/ProductoRoute.php';
require_once './routes/MesaRoute.php';
require_once './routes/PedidoRoute.php';

$app->run();