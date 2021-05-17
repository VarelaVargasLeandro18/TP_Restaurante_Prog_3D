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

//require_once './routes/PermisoEmpleadoSectorRoute.php';
require_once './routes/EmpleadoRoute.php';
require_once './routes/ProductoRoute.php';
require_once './routes/MesaRoute.php';

$app->run();