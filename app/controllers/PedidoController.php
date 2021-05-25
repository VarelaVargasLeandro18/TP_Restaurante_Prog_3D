<?php

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\StatusCode;

require_once __DIR__ . '/../models/PedidoModel.php';

class PedidoController implements ICRUDApi {

    public static function read(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $pedido = PedidoModel::readById($id);
        $jsonPedido = json_encode($pedido);

        if ( $pedido === NULL ) return $response->withStatus( StatusCodeInterface::STATUS_NOT_FOUND );

        $response->getBody()->write($jsonPedido);

        return $response->withAddedHeader('Content-Type', 'application/json');
    }

    public static function readAll(Request $request, Response $response, array $args): Response
    {
        $pedidos = PedidoModel::readAllObjects();
        $jsonPedidos = json_encode($pedidos);
        $response->getBody()->write($jsonPedidos);

        return $response->withAddedHeader('Content-Type', 'application/json');
    }

    public static function insert(Request $request, Response $response, array $args): Response
    {
        $json = $request->getBody();
        $assoc = json_decode($json, true);
        
        if ( $assoc === NULL ) return $response->withStatus( StatusCodeInterface::STATUS_BAD_REQUEST );

        $pedido = PedidoModel::crearpedido($assoc);
        $inserted = PedidoModel::insertObject($pedido);
        
        if ( !$inserted ) return $response->withStatus( StatusCodeInterface::STATUS_CONFLICT );
        // NO SE INSERTA SÍ, MAL TIPO O MAL SECTOR O USUARIO Y PASS USADOS.

        return $response->withStatus( StatusCodeInterface::STATUS_CREATED );
    }

    public static function delete(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $deleted = PedidoModel::deleteById($id);
        $jsonDeleted = json_encode($deleted);

        if ( $deleted === NULL ) return $response->withStatus( StatusCodeInterface::STATUS_NOT_FOUND );
        
        $response->getBody()->write($jsonDeleted);

        return $response->withAddedHeader('Content-Type', 'application/json');
    }

    public static function update(Request $request, Response $response, array $args): Response
    {
        $json = $request->getBody();
        $assoc = json_decode($json, true);

        $pedido = PedidoModel::crearPedido($assoc);
        $updated = PedidoModel::updateObject($pedido);

        if ( !$updated ) return $response->withStatus( StatusCodeInterface::STATUS_CONFLICT );
        // NO SE INSERTA SÍ, MAL TIPO O MAL SECTOR O USUARIO Y PASS USADOS.

        return $response->withStatus( StatusCodeInterface::STATUS_NO_CONTENT );
    }

}