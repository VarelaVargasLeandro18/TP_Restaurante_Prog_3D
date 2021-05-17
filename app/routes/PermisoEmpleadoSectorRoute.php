<?php

require_once __DIR__ . '/../controllers/PermisoEmpleadoSectorController.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$app->group('/PermisoEmpleadoSector', function () {

    $this->get( '/{id}', PermisosEmpleadoSectorController::class . ':readById' );

    $this->get( '/', PermisosEmpleadoSectorController::class . ':readAll' );

    $this->post(  )

});