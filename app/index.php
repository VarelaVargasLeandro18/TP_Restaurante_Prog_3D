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

$app->get('/', function () {
    require_once './models/EmpleadoModel.php';
    $obj = EmpleadoModel::insertObject(
        new Empleado( 0,
        '',
        '',
        new Sector(1, ''),
        new TipoEmpleado(1, ''),
        'B',
        'C' 
        )
    );
    var_dump($obj);
});

$app->run();