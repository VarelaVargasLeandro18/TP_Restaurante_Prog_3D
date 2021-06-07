<?php

require_once __DIR__ . '/controllers/ProductoController.php';

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Controllers\ProductoController;
use POPOs\Producto;

$app->get ( '/prueba/{id}', ProductoController::class . '::read' );
$app->get( '/prueba', ProductoController::class . '::readAll' );
$app->post( '/prueba', ProductoController::class . '::insert' );
$app->delete( '/prueba/{id}', ProductoController::class . '::delete' );
$app->put( '/prueba/{id}', ProductoController::class . '::update' );