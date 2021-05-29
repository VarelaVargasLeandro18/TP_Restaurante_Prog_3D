<?php

require __DIR__ . '/../middlewares/Logger.php';

use Illuminate\Support\Facades\Log;
use Slim\Routing\RouteCollectorProxy;

require_once __DIR__ . '/../controllers/EmpleadoController.php';

$app->group( '/empleado', function(RouteCollectorProxy $group) {

    $group->get( '/', EmpleadoController::class . ':readAll' );

    $group->get( '/{id}', EmpleadoController::class . ':read' );

    $group->post( '/', EmpleadoController::class . ':insert' );

    $group->delete( '/{id}', EmpleadoController::class . ':delete' );
    
    $group->put( '/', EmpleadoController::class . ':update' );

} )
->add( Logger::class . ':logSocio' );