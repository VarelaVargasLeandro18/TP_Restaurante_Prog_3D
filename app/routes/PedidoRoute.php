<?php

require_once __DIR__ . '/../controllers/PedidoController.php';
require_once __DIR__ . '/../controllers/PedidoProductoController.php';

use Controllers\PedidoController;
use Controllers\PedidoProductoController;
use Slim\Routing\RouteCollectorProxy as RCP;

$app->group( '/pedido', function ( RCP $group ) {

    $group->get( '/', PedidoController::class . '::readAll' );
    $group->get('/{id}', PedidoController::class . '::read' );
    $group->post( '/', PedidoController::class . '::insert' );
    $group->delete( '/{id}', PedidoController::class . '::delete' );
    $group->put( '/{id}', PedidoController::class . '::update' );

    $group->group( '/producto', function ( RCP $groupPP ) {
        
        $groupPP->get( '/{codigoPedido}', PedidoProductoController::class . '::obtenerProductosDePedido' );
        $groupPP->post( '/', PedidoProductoController::class . '::insert' );
        $groupPP->delete( '/{id}', PedidoProductoController::class . '::delete' );
        $groupPP->put( '/{id}', PedidoProductoController::class . '::update' );

    } );

} );