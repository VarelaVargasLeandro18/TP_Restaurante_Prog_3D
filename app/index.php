<?php

use Dotenv\Dotenv;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require '../vendor/autoload.php';

$dotEnv = \Dotenv\Dotenv::createImmutable('../');
$dotEnv->load();

$settings = [ 
    'addContentLengthHeader' => false
    ,'displayErrorDetails' => true
];

$app = new \Slim\App( [ 'settings' => $settings ] );

$app->get( "/", function (Request $req, Response $res) {
    require_once './models/EstadoMesaModel.php';
    $obj = EstadoMesaModel::updateObject(new EstadoMesa(1, 'cerrada'));
    var_dump($obj);
} );

$app->run();