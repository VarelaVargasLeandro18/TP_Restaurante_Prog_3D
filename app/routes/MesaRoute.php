<?php

require_once __DIR__ . '/../controllers/MesaController.php';

$app->group( '/mesa', function() {

    $this->get( '/', MesaController::class . ':readAll' );

    $this->get( '/{id}', MesaController::class . ':read' );

    $this->post( '/', MesaController::class . ':insert' );

    $this->delete( '/{id}', MesaController::class . ':delete' );
    
    $this->put( '/', MesaController::class . ':update' );

} );