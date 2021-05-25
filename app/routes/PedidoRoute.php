<?php

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteCollectorProxy;

require_once __DIR__ . '/../controllers/PedidoController.php';

$app->group( '/pedido', function(RouteCollectorProxy $group) {

    $group->get( '/', PedidoController::class . ':readAll' );

    $group->get( '/{id}', PedidoController::class . ':read' );

    $group->post( '/', PedidoController::class . ':insert' );

    $group->delete( '/{id}', PedidoController::class . ':delete' );
    
    $group->put( '/', PedidoController::class . ':update' );

} );