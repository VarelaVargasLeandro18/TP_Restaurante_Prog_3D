<?php

require_once __DIR__ . '/../controllers/PedidoController.php';
require_once __DIR__ . '/../controllers/PedidoProductoController.php';
require_once __DIR__ . '/../middlewares/LogIn.php';
require_once __DIR__ . '/../middlewares/UsrAuthorizationMW.php';

use Controllers\PedidoController;
use Controllers\PedidoProductoController;
use Slim\Routing\RouteCollectorProxy as RCP;

use Middleware\LogIn as LI;
use Middleware\UsrAuthorizationMW as UAMW;

$app->group( '/pedido', function ( RCP $group ) {

    PedidoController::$imgPath = '/img/Pedidos/';

    $group->get( '/', PedidoController::class . '::readAll' );
    $group->get('/{id}', PedidoController::class . '::read' );
    $group->post( '/', PedidoController::class . '::insert' );
    $group->post( '/imagen/{codigoPedido}', PedidoController::class . '::agregarImagen' );
    $group->delete( '/{id}', PedidoController::class . '::delete' );
    $group->put( '/{id}', PedidoController::class . '::update' );

} )->add( UAMW::class . '::permitirSocio' );

$app->group( '/pedido/producto', function ( RCP $group ) {

    $group->get( '/', PedidoProductoController::class . '::readAll' );
    $group->get( '/{id}', PedidoProductoController::class . '::read' );
    $group->delete( '/{id}', PedidoProductoController::class . '::delete' );
    $group->put( '/{id}', PedidoProductoController::class . '::update' );

} )->add( UAMW::class . '::permitirSocio' );

$app->group( '/pedidos', function ( RCP $group ) {

    /* Lista pedidos disponibles para un usuario determinado. */
    $group->get( '/listar', PedidoProductoController::class . '::obtenerProductosDePedido' );
    /* Toma un pedido con un código determinado siempre que se pueda. */
    $group->get( '/tomarUno/{id}', PedidoProductoController::class . '::tomarPedido' );

} )->add(UAMW::class . '::restringirCliente')->add ( LI::class . '::obtenerUsuario' );