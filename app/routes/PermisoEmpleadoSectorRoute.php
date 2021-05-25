<?php

require_once __DIR__ . '/../controllers/PermisoEmpleadoSectorController.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteCollectorProxy;


$app->group('/PermisoEmpleadoSector', function (RouteCollectorProxy $group) {

    $group->get( '/{id}', PermisosEmpleadoSectorController::class . ':readById' );

    $group->get( '/', PermisosEmpleadoSectorController::class . ':readAll' );

    $group->post(  )

});