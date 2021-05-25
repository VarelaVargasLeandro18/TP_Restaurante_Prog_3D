<?php

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require_once __DIR__ . '/../models/ProductoModel.php';

class ProductoController implements ICRUDApi {

    public static function read(Request $request, Response $response, array $args): Response
    {
        $id = intval($args['id']);
        $producto = ProductoModel::readById($id);
        $jsonProducto = json_encode($producto);

        if ( $producto === NULL ) return $response->withStatus( StatusCodeInterface::STATUS_NOT_FOUND );

        $response->getBody()->write($jsonProducto);

        return $response->withAddedHeader('Content-Type', 'application/json');
    }

    public static function readAll(Request $request, Response $response, array $args): Response
    {
        $productos = ProductoModel::readAllObjects();
        $jsonProductos = json_encode($productos);
        $response->getBody()->write($jsonProductos);

        return $response->withAddedHeader('Content-Type', 'application/json');
    }

    public static function insert(Request $request, Response $response, array $args): Response
    {
        $json = $request->getBody();
        $assoc = json_decode($json, true);
        
        if ( $assoc === NULL ) return $response->withStatus( StatusCodeInterface::STATUS_BAD_REQUEST );

        $producto = ProductoModel::crearproducto($assoc);
        $inserted = ProductoModel::insertObject($producto);
        
        if ( !$inserted ) return $response->withStatus( StatusCodeInterface::STATUS_CONFLICT );
        // NO SE INSERTA SÍ, MAL TIPO O MAL SECTOR O USUARIO Y PASS USADOS.

        return $response->withStatus( StatusCodeInterface::STATUS_CREATED );
    }

    public static function delete(Request $request, Response $response, array $args): Response
    {
        $id = intval($args['id']);
        $deleted = ProductoModel::deleteById($id);
        $jsonDeleted = json_encode($deleted);

        if ( $deleted === NULL ) return $response->withStatus( StatusCodeInterface::STATUS_NOT_FOUND );
        
        $response->getBody()->write($jsonDeleted);

        return $response->withAddedHeader('Content-Type', 'application/json');
    }

    public static function update(Request $request, Response $response, array $args): Response
    {
        $json = $request->getBody();
        $assoc = json_decode($json, true);

        $producto = ProductoModel::crearProducto($assoc);
        $updated = ProductoModel::updateObject($producto);

        if ( !$updated ) return $response->withStatus( StatusCodeInterface::STATUS_CONFLICT );
        // NO SE INSERTA SÍ, MAL TIPO O MAL SECTOR O USUARIO Y PASS USADOS.

        return $response->withStatus( StatusCodeInterface::STATUS_NO_CONTENT );
    }

}