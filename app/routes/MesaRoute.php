<?php

require_once __DIR__ . '/../controllers/MesaController.php';
require_once __DIR__ . '/../middlewares/LogIn.php';

use Controllers\MesaController as MC;

use Slim\Routing\RouteCollectorProxy as RCP;

use Middleware\LogIn as LI;

$app->group ( '/mesa', function ( RCP $group ) {

    LI::$permitidos = array( 'socio' => 1 );

    $group->get('/', MC::class . '::readAll' );
    $group->get( '/{id}', MC::class . '::read' );
    $group->post( '/', MC::class . '::insert' );
    $group->delete ( '/{id}', MC::class . '::delete' );
    $group->put ( '/{id}', MC::class . '::update' );

} )->add( LI::class . '::permitirAccesoPorTipo' );

