<?php

require_once __DIR__ . '/../controllers/PedidoController.php';
require_once __DIR__ . '/../controllers/PedidoProductoController.php';

use Controllers\PedidoController;
use Controllers\PedidoProductoController;
use Slim\Routing\RouteCollectorProxy as RCP;

require_once __DIR__ . '/../middlewares/LogIn.php';
use Middleware\LogIn as LI;

$app->group( '/pedido', function ( RCP $group ) {

    LI::$permitidos = array( 'socio' => 1 );

    PedidoController::$imgPath = '/img/Pedidos/';

    $group->get( '/', PedidoController::class . '::readAll' );
    $group->get('/{id}', PedidoController::class . '::read' );
    $group->post( '/', PedidoController::class . '::insert' );
    $group->post( '/imagen/{codigoPedido}', PedidoController::class . '::agregarImagen' );
    $group->delete( '/{id}', PedidoController::class . '::delete' );
    $group->put( '/{id}', PedidoController::class . '::update' );

    $group->group( '/producto', function ( RCP $groupPP ) {
        
        $groupPP->get( '/{codigoPedido}', PedidoProductoController::class . '::obtenerProductosDePedido' );
        $groupPP->post( '/{codigoPedido}', PedidoProductoController::class . '::agregarImagen' );
        $groupPP->delete( '/{id}', PedidoProductoController::class . '::delete' );
        $groupPP->put( '/{id}', PedidoProductoController::class . '::update' );

    } );

} )->add( LI::class . '::permitirAccesoPorTipo' );
