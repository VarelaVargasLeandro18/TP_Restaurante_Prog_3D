<?php
error_reporting(-1);
ini_set('display_errors', -1);
date_default_timezone_set( 'America/Argentina/Buenos_Aires' );

use GuzzleHttp\Psr7\Response;
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

$app->get ( '/', function () {
    require_once __DIR__ . '/Pojos/TipoComentario.php';
    require_once __DIR__ . '/db/DoctrineEntityManagerFactory.php';
    
    $TCRep = DoctrineEntityManagerFactory::getEntityManager()->getRepository(TipoComentario::class);
    
    return new Response( 200, [], $TCRep->find(1) );
} );

/*require_once './routes/EmpleadoRoute.php';
require_once './routes/ProductoRoute.php';
require_once './routes/MesaRoute.php';
require_once './routes/PedidoRoute.php';
require_once './routes/AuthRoute.php';*/

$app->run();