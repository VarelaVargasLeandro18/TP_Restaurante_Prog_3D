<?php

require_once __DIR__ . '/../controllers/ProductoController.php';
require_once __DIR__ . '/../middlewares/LogIn.php';

use Controllers\ProductoController;
use Slim\Routing\RouteCollectorProxy as RCP;
use Middleware\LogIn as LI;


$app->group( '/producto', function ( RCP $group ) {

    $group->get( '/', ProductoController::class . '::readAll' );
    $group->get('/{id}', ProductoController::class . '::read' );
    $group->post( '/', ProductoController::class . '::insert' );
    $group->delete( '/{id}', ProductoController::class . '::delete' );
    $group->put( '/{id}', ProductoController::class . '::update' );

} );

