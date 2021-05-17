<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\StatusCode;

require_once __DIR__ . '/../models/EmpleadoModel.php';
require_once __DIR__ . '/../interfaces/ICRUDApi.php';

class EmpleadoController implements ICRUDApi {

    public static function read(Request $request, Response $response, array $args): Response
    {
        $id = intval($args['id']);
        $empleado = EmpleadoModel::readById($id);

        if ( $empleado === NULL ) return $response->withStatus( StatusCode::HTTP_NOT_FOUND );

        return $response->withJson($empleado);
    }

    public static function readAll(Request $request, Response $response, array $args): Response
    {
        $empleados = EmpleadoModel::readAllObjects();
        return $response->withJson($empleados);
    }

    public static function insert(Request $request, Response $response, array $args): Response
    {
        $json = $request->getBody();
        $assoc = json_decode($json, true);
        
        if ( $assoc === NULL ) return $response->withStatus( StatusCode::HTTP_BAD_REQUEST );

        $empleado = EmpleadoModel::crearEmpleado($assoc);
        $inserted = EmpleadoModel::insertObject($empleado);
        
        if ( !$inserted ) return $response->withStatus( StatusCode::HTTP_CONFLICT );
        // NO SE INSERTA SÍ, MAL TIPO O MAL SECTOR O USUARIO Y PASS USADOS.

        return $response->withStatus( StatusCode::HTTP_CREATED );
    }

    public static function delete(Request $request, Response $response, array $args): Response
    {
        $id = intval($args['id']);
        $deleted = EmpleadoModel::deleteById($id);

        if ( $deleted === NULL ) return $response->withStatus( StatusCode::HTTP_NOT_FOUND );
        
        return $response->withJson( $deleted );
    }

    public static function update(Request $request, Response $response, array $args): Response
    {
        $json = $request->getBody();
        $assoc = json_decode($json, true);

        $empleado = EmpleadoModel::crearEmpleado($assoc);
        $updated = EmpleadoModel::updateObject($empleado);

        if ( !$updated ) return $response->withStatus( StatusCode::HTTP_CONFLICT );
        // NO SE INSERTA SÍ, MAL TIPO O MAL SECTOR O USUARIO Y PASS USADOS.

        return $response->withStatus( StatusCode::HTTP_NO_CONTENT );

    }

}