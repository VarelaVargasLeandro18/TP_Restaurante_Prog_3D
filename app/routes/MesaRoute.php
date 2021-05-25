<?php

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteCollectorProxy;

require_once __DIR__ . '/../controllers/MesaController.php';

$app->group( '/mesa', function(RouteCollectorProxy $group) {

    $group->get( '/', MesaController::class . ':readAll' );

    $group->get( '/{id}', MesaController::class . ':read' );

    $group->post( '/', MesaController::class . ':insert' );

    $group->delete( '/{id}', MesaController::class . ':delete' );
    
    $group->put( '/', MesaController::class . ':update' );

} );