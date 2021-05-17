<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\StatusCode;

require_once __DIR__ . '/../models/MesaModel.php';

class MesaController implements ICRUDApi {

    public static function read(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $mesa = MesaModel::readById($id);

        if ( $mesa === NULL ) return $response->withStatus( StatusCode::HTTP_NOT_FOUND );

        return $response->withJson($mesa);
    }

    public static function readAll(Request $request, Response $response, array $args): Response
    {
        $mesas = MesaModel::readAllObjects();
        return $response->withJson($mesas);
    }

    public static function insert(Request $request, Response $response, array $args): Response
    {
        $json = $request->getBody();
        $assoc = json_decode($json, true);
        
        if ( $assoc === NULL ) return $response->withStatus( StatusCode::HTTP_BAD_REQUEST );

        $mesa = MesaModel::crearmesa($assoc);
        $inserted = MesaModel::insertObject($mesa);
        
        if ( !$inserted ) return $response->withStatus( StatusCode::HTTP_CONFLICT );
        // NO SE INSERTA SÍ, MAL TIPO O MAL SECTOR O USUARIO Y PASS USADOS.

        return $response->withStatus( StatusCode::HTTP_CREATED );
    }

    public static function delete(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $deleted = MesaModel::deleteById($id);

        if ( $deleted === NULL ) return $response->withStatus( StatusCode::HTTP_NOT_FOUND );
        
        return $response->withJson( $deleted );
    }

    public static function update(Request $request, Response $response, array $args): Response
    {
        $json = $request->getBody();
        $assoc = json_decode($json, true);

        $mesa = MesaModel::crearMesa($assoc);
        $updated = MesaModel::updateObject($mesa);

        if ( !$updated ) return $response->withStatus( StatusCode::HTTP_CONFLICT );
        // NO SE INSERTA SÍ, MAL TIPO O MAL SECTOR O USUARIO Y PASS USADOS.

        return $response->withStatus( StatusCode::HTTP_NO_CONTENT );
    }

}