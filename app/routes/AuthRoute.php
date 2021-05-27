<?php

use Slim\Routing\RouteCollectorProxy;

require_once __DIR__ . '/../controllers/AuthController.php';

$app->group( '/auth', function(RouteCollectorProxy $group) {

    $group->get( '/', AuthController::class . ':getToken'  );

} );