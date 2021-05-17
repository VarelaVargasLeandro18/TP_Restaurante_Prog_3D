<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\StatusCode;

require_once __DIR__ . '/../models/ProductoModel.php';

class ProductoController implements ICRUDApi {

    public static function read(Request $request, Response $response, array $args): Response
    {
        $id = intval($args['id']);
        $producto = ProductoModel::readById($id);

        if ( $producto === NULL ) return $response->withStatus( StatusCode::HTTP_NOT_FOUND );

        return $response->withJson($producto);
    }

    public static function readAll(Request $request, Response $response, array $args): Response
    {
        $productos = ProductoModel::readAllObjects();
        return $response->withJson($productos);
    }

    public static function insert(Request $request, Response $response, array $args): Response
    {
        $json = $request->getBody();
        $assoc = json_decode($json, true);
        
        if ( $assoc === NULL ) return $response->withStatus( StatusCode::HTTP_BAD_REQUEST );

        $producto = ProductoModel::crearproducto($assoc);
        $inserted = ProductoModel::insertObject($producto);
        
        if ( !$inserted ) return $response->withStatus( StatusCode::HTTP_CONFLICT );
        // NO SE INSERTA SÍ, MAL TIPO O MAL SECTOR O USUARIO Y PASS USADOS.

        return $response->withStatus( StatusCode::HTTP_CREATED );
    }

    public static function delete(Request $request, Response $response, array $args): Response
    {
        $id = intval($args['id']);
        $deleted = ProductoModel::deleteById($id);

        if ( $deleted === NULL ) return $response->withStatus( StatusCode::HTTP_NOT_FOUND );
        
        return $response->withJson( $deleted );
    }

    public static function update(Request $request, Response $response, array $args): Response
    {
        $json = $request->getBody();
        $assoc = json_decode($json, true);

        $producto = ProductoModel::crearProducto($assoc);
        $updated = ProductoModel::updateObject($producto);

        if ( !$updated ) return $response->withStatus( StatusCode::HTTP_CONFLICT );
        // NO SE INSERTA SÍ, MAL TIPO O MAL SECTOR O USUARIO Y PASS USADOS.

        return $response->withStatus( StatusCode::HTTP_NO_CONTENT );
    }

}