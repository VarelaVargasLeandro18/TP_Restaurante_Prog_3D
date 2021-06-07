<?php

require_once __DIR__ . '/models/ComentarioModel.php';

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Models\ComentarioModel as TUM;

$app->get ( '/prueba', function ( RequestInterface $request, ResponseInterface $response, array $args ) {

    $model = new TUM();
    $finded = $model->readAllObjects();
    
    $ret = json_encode( $finded , JSON_INVALID_UTF8_SUBSTITUTE);
    
    
    return new Response ( 200, array('Content-Type' => 'application/json' ), $ret );
} );