<?php
error_reporting(-1);
ini_set('display_errors', -1);
date_default_timezone_set( 'America/Argentina/Buenos_Aires' );

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

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

$app->get( '/', function (Request $req, Response $res) {
    require_once __DIR__ . '/../app/models/AbstractCRUD.php';
    $crud = new AbstractCRUD('Empleado', ['id' => PDO::PARAM_INT, 'nombre' => PDO::PARAM_STR, 'apellido' => PDO::PARAM_STR], 'id');
    //$crud->create( ['id' => 0, 'nombre' => 'lean'] );
    //var_dump(  $crud->readAll( ['id', 'nombre', 'FechaIngreso', 'TipoEmpleadoId'] ) );
    //var_dump( $crud->deleteById(10) );
    //var_dump( $crud->query( 'SELECT * FROM Empleado WHERE id = :id', [ 'id' => 8 ] ) );
    return $res;
} );

/*require_once './routes/EmpleadoRoute.php';
require_once './routes/ProductoRoute.php';
require_once './routes/MesaRoute.php';
require_once './routes/PedidoRoute.php';
require_once './routes/AuthRoute.php';*/

$app->run();