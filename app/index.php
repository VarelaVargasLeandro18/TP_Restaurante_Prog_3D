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
    require_once './models/SectorModel.php';
    $obj = SectorModel::updateObject(new Sector(1, 'Barra de tragos y vinos'));
    var_dump($obj);
} );

$app->run();