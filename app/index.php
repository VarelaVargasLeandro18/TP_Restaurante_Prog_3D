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
    require_once './models/TipoEmpleadoModel.php';
    $obj = TipoEmpleadoModel::insertObject(new TipoEmpleado(7, "#socio"));
    var_dump($obj);
} );

$app->run();