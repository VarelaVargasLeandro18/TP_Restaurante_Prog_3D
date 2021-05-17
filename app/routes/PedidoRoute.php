<?php

require_once __DIR__ . '/../controllers/PedidoController.php';

$app->group( '/pedido', function() {

    $this->get( '/', PedidoController::class . ':readAll' );

    $this->get( '/{id}', PedidoController::class . ':read' );

    $this->post( '/', PedidoController::class . ':insert' );

    $this->delete( '/{id}', PedidoController::class . ':delete' );
    
    $this->put( '/', PedidoController::class . ':update' );

} );