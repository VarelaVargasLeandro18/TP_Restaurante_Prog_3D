<?php

require_once __DIR__ . '/../controllers/ProductoController.php';

$app->group( '/producto', function() {

    $this->get( '/', ProductoController::class . ':readAll' );

    $this->get( '/{id}', ProductoController::class . ':read' );

    $this->post( '/', ProductoController::class . ':insert' );

    $this->delete( '/{id}', ProductoController::class . ':delete' );
    
    $this->put( '/', ProductoController::class . ':update' );

} );