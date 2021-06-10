<?php

require_once __DIR__ . '/../models/PedidoProductoModel.php';
require_once __DIR__ . '/../models/PedidoModel.php';

use GuzzleHttp\Psr7\Response;
use Models\PedidoModel as PM;

$app->get ( "/", function ( ) {
    $pm = new PM();
    $pedido = $pm->readById("fdae4");
    $ret = json_encode($pedido, JSON_INVALID_UTF8_SUBSTITUTE);
        
    return new Response( 200, array('Content-Type' => 'application/json'), $ret );
} );