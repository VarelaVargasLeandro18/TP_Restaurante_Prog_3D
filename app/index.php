<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require '../vendor/autoload.php';

$settings = [ 
    'addContentLengthHeader' => false,
    'displayErrorDetails' => true
];

$app = new \Slim\App( [ 'settings' => $settings ] );



$app->run();