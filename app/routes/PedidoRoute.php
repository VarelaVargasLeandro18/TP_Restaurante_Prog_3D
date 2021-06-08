<?php

require_once __DIR__ . '/../controllers/PedidoController.php';
use Controllers\PedidoController;
use Slim\Routing\RouteCollectorProxy as RCP;

$app->group( '/pedido', function ( RCP $group ) {

    $group->get( '/', PedidoController::class . '::readAll' );
    $group->get('/{id}', PedidoController::class . '::read' );
    $group->post( '/', PedidoController::class . '::insert' );
    $group->delete( '/{id}', PedidoController::class . '::delete' );
    $group->put( '/{id}', PedidoController::class . '::update' );

} );