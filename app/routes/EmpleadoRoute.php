<?php

require_once __DIR__ . '/../controllers/EmpleadoController.php';

$app->group( '/empleado', function() {

    $this->get( '/', EmpleadoController::class . ':readAll' );

    $this->get( '/{id}', EmpleadoController::class . ':read' );

    $this->post( '/', EmpleadoController::class . ':insert' );

    

} );