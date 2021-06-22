<?php

require_once __DIR__ . '/../controllers/AuthController.php';

use Controllers\AuthController as AC;
use Slim\Routing\RouteCollectorProxy as RCP;

$app->group ( "/token", function ( RCP $group ) {

    $group->get( '/', AC::class . '::getToken' );
    $group->get( '/check/', AC::class . '::checkToken' );

} );