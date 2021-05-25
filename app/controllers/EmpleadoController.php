<?php

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require_once __DIR__ . '/../models/EmpleadoModel.php';
require_once __DIR__ . '/../beans/Empleado.php';
require_once __DIR__ . '/../interfaces/ICRUDApi.php';

class EmpleadoController implements ICRUDApi {

    public static function read(Request $request, Response $response, array $args): Response
    {
        $id = intval($args['id']);
        $empleado = EmpleadoModel::readById($id);
        $jsonEmpleado = json_encode( $empleado );

        if ( $empleado === NULL ) return $response->withStatus( StatusCodeInterface::STATUS_NOT_FOUND );
        
        $response->getBody()->write($jsonEmpleado);

        return $response->withAddedHeader('Content-Type', 'application/json');
    }

    public static function readAll(Request $request, Response $response, array $args): Response
    {
        $empleados = EmpleadoModel::readAllObjects();
        $jsonEmpleados = json_encode( $empleados );
        $response->getBody()->write($jsonEmpleados);    

        return $response->withAddedHeader('Content-Type', 'application/json');
    }

    public static function insert(Request $request, Response $response, array $args): Response
    {
        $json = $request->getBody();
        $empleado = Empleado::decode($json);
        
        if ( $empleado === NULL ) return $response->withStatus( StatusCodeInterface::STATUS_BAD_REQUEST );
        
        $inserted = EmpleadoModel::insertObject($empleado);
        
        if ( !$inserted ) return $response->withStatus( StatusCodeInterface::STATUS_CONFLICT );
        // NO SE INSERTA SÍ, MAL TIPO O MAL SECTOR O USUARIO Y PASS USADOS.

        return $response->withStatus( StatusCodeInterface::STATUS_CREATED );
    }

    public static function delete(Request $request, Response $response, array $args): Response
    {
        $id = intval($args['id']);
        $deleted = EmpleadoModel::deleteById($id);
        $jsonDeleted = json_encode($deleted);

        if ( $deleted === NULL ) return $response->withStatus( StatusCodeInterface::STATUS_NOT_FOUND );

        $response->getBody()->write($jsonDeleted);
        
        return $response->withAddedHeader('Content-Type', 'application/json');
    }

    public static function update(Request $request, Response $response, array $args): Response
    {
        $json = $request->getBody();
        $empleado = Empleado::decode($json);

        if ( $empleado === NULL ) return $response->withStatus(StatusCodeInterface::STATUS_BAD_REQUEST);

        $updated = EmpleadoModel::updateObject($empleado);

        if ( !$updated ) return $response->withStatus( StatusCodeInterface::STATUS_CONFLICT );
        // NO SE INSERTA SÍ, MAL TIPO O MAL SECTOR O USUARIO Y PASS USADOS.

        return $response->withStatus( StatusCodeInterface::STATUS_NO_CONTENT );
    }

}