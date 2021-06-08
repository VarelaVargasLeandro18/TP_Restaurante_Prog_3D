<?php

require_once __DIR__ . '/../controllers/MesaController.php';

use Controllers\MesaController as MC;
use Slim\Routing\RouteCollectorProxy as RCP;

$app->group ( '/mesa', function ( RCP $group ) {

    $group->get('/', MC::class . '::readAll' );
    $group->get( '/{id}', MC::class . '::read' );
    $group->post( '/', MC::class . '::insert' );
    $group->delete ( '/{id}', MC::class . '::delete' );
    $group->put ( '/{id}', MC::class . '::update' );

} );
