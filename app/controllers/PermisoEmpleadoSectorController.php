<?php

require_once __DIR__ . '/../interfaces/ICRUDApi.php';
require_once __DIR__ . '/../models/PermisosEmpleadoSectorModel.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class PermisosEmpleadoSectorController implements ICRUDApi
{

    public static function read (Request $request, Response $response, array $args): Response
    {
        $id = intval($args['id']);
        $empleado = PermisosEmpleadoSectorModel::readById($id);
        $newResponse = $response->withJson($empleado);
        return $newResponse;
    }

    public static function readAll (Request $request, Response $response, array $args): Response
    {
        $empleadosPerSector = PermisosEmpleadoSectorModel::readAllObjects();
        $newResponse = $response->withJson($empleadosPerSector);
        return $newResponse;
    }

    public static function insert(Request $request, Response $response, array $args): Response
    {
        $json = $request->getBody();
        $assocPermisos = json_decode( $json, true );
        $assocTipo = json_decode( $assocPermisos['tipo'], true );
        $tipo = new TipoEmpleado( intval($assocTipo['']) )

        $permisos = new PermisosEmpleadoSector( '0',  )

    }

}