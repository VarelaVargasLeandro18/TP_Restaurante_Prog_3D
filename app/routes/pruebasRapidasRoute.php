<?php

require_once __DIR__ . '/../models/OperacionHistorialModel.php';

use GuzzleHttp\Psr7\Response;
use Models\OperacionHistorialModel as TOPM;
use Slim\Routing\RouteCollectorProxy;

$app->get ( "/", function ( ) {

    $tom = new TOPM();
    $tops = $tom->readAllObjects();
    $ret = json_encode($tops, JSON_INVALID_UTF8_SUBSTITUTE);
    return new Response( 200, array('Content-Type' => 'application/json'), $ret );
} );