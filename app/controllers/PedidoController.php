<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\StatusCode;

require_once __DIR__ . '/../models/PedidoModel.php';

class PedidoController implements ICRUDApi {

    public static function read(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $pedido = PedidoModel::readById($id);

        if ( $pedido === NULL ) return $response->withStatus( StatusCode::HTTP_NOT_FOUND );

        return $response->withJson($pedido);
    }

    public static function readAll(Request $request, Response $response, array $args): Response
    {
        $pedidos = PedidoModel::readAllObjects();
        return $response->withJson($pedidos);
    }

    public static function insert(Request $request, Response $response, array $args): Response
    {
        $json = $request->getBody();
        $assoc = json_decode($json, true);
        
        if ( $assoc === NULL ) return $response->withStatus( StatusCode::HTTP_BAD_REQUEST );

        $pedido = PedidoModel::crearpedido($assoc);
        $inserted = PedidoModel::insertObject($pedido);
        
        if ( !$inserted ) return $response->withStatus( StatusCode::HTTP_CONFLICT );
        // NO SE INSERTA SÍ, MAL TIPO O MAL SECTOR O USUARIO Y PASS USADOS.

        return $response->withStatus( StatusCode::HTTP_CREATED );
    }

    public static function delete(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $deleted = PedidoModel::deleteById($id);

        if ( $deleted === NULL ) return $response->withStatus( StatusCode::HTTP_NOT_FOUND );
        
        return $response->withJson( $deleted );
    }

    public static function update(Request $request, Response $response, array $args): Response
    {
        $json = $request->getBody();
        $assoc = json_decode($json, true);

        $pedido = PedidoModel::crearPedido($assoc);
        $updated = PedidoModel::updateObject($pedido);

        if ( !$updated ) return $response->withStatus( StatusCode::HTTP_CONFLICT );
        // NO SE INSERTA SÍ, MAL TIPO O MAL SECTOR O USUARIO Y PASS USADOS.

        return $response->withStatus( StatusCode::HTTP_NO_CONTENT );
    }

}