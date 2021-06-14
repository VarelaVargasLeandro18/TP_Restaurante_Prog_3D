<?php

require_once __DIR__ . '/../controllers/PedidoController.php';
require_once __DIR__ . '/../controllers/PedidoProductoController.php';
require_once __DIR__ . '/../middlewares/LogIn.php';
require_once __DIR__ . '/../middlewares/UsrAuthorizationMW.php';
require_once __DIR__ . '/../middlewares/Logger.php';

use Controllers\PedidoController;
use Controllers\PedidoProductoController;
use Slim\Routing\RouteCollectorProxy as RCP;

use Middleware\Logger as L;
use Middleware\UsrAuthorizationMW as UAMW;

$app->group( '/pedido', function ( RCP $group ) {

    PedidoController::$imgPath = '/img/Pedidos/';

    $group->get( '/', PedidoController::class . '::readAll' );
    $group->get('/{id}', PedidoController::class . '::read' );
    $group->post( '/', PedidoController::class . '::insert' );
    $group->delete( '/{id}', PedidoController::class . '::delete' );
    $group->put( '/{id}', PedidoController::class . '::update' );

    $group->post( '/imagen/{codigoPedido}', PedidoController::class . '::agregarImagen' );

} )
    ->add( UAMW::class . '::permitirSocio' )
    ->add ( L::class . '::loggerOperacionPedidos' );

$app->group( '/pedido/producto', function ( RCP $group ) {

    $group->get( '/', PedidoProductoController::class . '::readAll' );
    $group->get( '/{id}', PedidoProductoController::class . '::read' );
    $group->delete( '/{id}', PedidoProductoController::class . '::delete' );
    $group->put( '/{id}', PedidoProductoController::class . '::update' );

} )
    ->add( UAMW::class . '::permitirSocio' )
    ->add ( L::class . '::loggerOperacionPedidos' );

$app->group( '/pedidos', function ( RCP $group ) {

    /* Lista pedidos disponibles para un usuario determinado. */
    $group->get( '/listar', PedidoProductoController::class . '::obtenerProductosDePedido' );
    /* Toma un pedido con un código determinado siempre que se pueda. */
    $group->put( '/tomar/{id}', PedidoProductoController::class . '::tomarPedido' );
    /* Establece un pedido como finalizado. */
    $group->put( '/terminar/{id}', PedidoProductoController::class . '::terminarPedido' );

} )
    ->add(UAMW::class . '::restringirCliente')
    ->add ( L::class . '::loggerOperacionPedidos' );