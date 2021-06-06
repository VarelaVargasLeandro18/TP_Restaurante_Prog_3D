<?php
error_reporting(-1);
ini_set('display_errors', -1);
date_default_timezone_set( 'America/Argentina/Buenos_Aires' );

require_once __DIR__ . '/models/SectorModel.php';
require_once __DIR__ . '/POPOs/Sector.php';

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Models\SectorModel as SM;
use POPOs\Sector;
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

$app->get( '/prueba', function ( RequestInterface $request, ResponseInterface $response, array $args ) {
    $sm = new SM();
    $ret = json_encode($sm->readAllObjects());
    
    return new Response( 200, array('Content-type' => 'application/json'),  $ret);
} );

$app->run();