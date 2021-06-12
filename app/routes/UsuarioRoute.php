<?php

require_once __DIR__ . '/../controllers/UsuarioController.php';
require_once __DIR__ . '/../middlewares/LogIn.php';

use Slim\Routing\RouteCollectorProxy as RCP;

use Controllers\UsuarioController as UC;

use Middleware\LogIn as LI;

$app->group ( '/usuario', function ( RCP $group ) {

    LI::$permitidos = array ( 'socio' => 1 );

    $group->get('/', UC::class . '::readAll' );
    $group->get( '/{id}', UC::class . '::read' );
    $group->post( '/', UC::class . '::insert' );
    $group->delete ( '/{id}', UC::class . '::delete' );
    $group->put ( '/{id}', UC::class . '::update' );

} )->add( LI::class . '::permitirAccesoPorTipo' );
