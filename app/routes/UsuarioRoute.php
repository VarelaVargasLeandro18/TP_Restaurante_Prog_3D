<?php

require_once __DIR__ . '/../controllers/UsuarioController.php';

require_once __DIR__ . '/../middlewares/Logger.php';
require_once __DIR__ . '/../middlewares/UsrAuthorizationMW.php';

use Slim\Routing\RouteCollectorProxy as RCP;

use Controllers\UsuarioController as UC;

use Middleware\Logger as L;
use Middleware\UsrAuthorizationMW as UAMW;

$app->group ( '/usuario', function ( RCP $group ) {

    $group->get('/', UC::class . '::readAll' );
    $group->get( '/{id}', UC::class . '::read' );
    $group->post( '/', UC::class . '::insert' );
    $group->delete ( '/{id}', UC::class . '::delete' );
    $group->put ( '/{id}', UC::class . '::update' );

    $group->put('/suspender/{id}', UC::class . '::suspenderEmpleado');
    $group->put('/continuar/{id}', UC::class . '::continuarEmpleado');
    
    $group->post ( '/archivo', UC::class . '::crearUsuariosDeArchivo' );

} )
    ->add( UAMW::class . '::permitirSocio' )
    ->add( L::class . '::loggerOperacionUsuario' );
