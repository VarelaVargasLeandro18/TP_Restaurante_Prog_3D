<?php

require_once __DIR__ . '/../controllers/ProductoController.php';
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteCollectorProxy;

$app->group( '/producto', function(RouteCollectorProxy $group) {

    $group->get( '/', ProductoController::class . ':readAll' );

    $group->get( '/{id}', ProductoController::class . ':read' );

    $group->post( '/', ProductoController::class . ':insert' );

    $group->delete( '/{id}', ProductoController::class . ':delete' );
    
    $group->put( '/', ProductoController::class . ':update' );

} );