<?php

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\StatusCode;

require_once __DIR__ . '/../models/MesaModel.php';

class MesaController implements ICRUDApi {

    public static function read(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $mesa = MesaModel::readById($id);
        $jsonMesa = json_encode($mesa);

        if ( $mesa === NULL ) return $response->withStatus( StatusCodeInterface::STATUS_NOT_FOUND );

        $response->getBody()->write($jsonMesa);

        return $response->withAddedHeader('Content-Type', 'json/application');
    }

    public static function readAll(Request $request, Response $response, array $args): Response
    {
        $mesas = MesaModel::readAllObjects();
        $jsonMesas = json_encode($mesas);
        $response->getBody()->write($jsonMesas);

        return $response->withAddedHeader('Content-Type', 'application/json');
    }

    public static function insert(Request $request, Response $response, array $args): Response
    {
        $json = $request->getBody();
        $assoc = json_decode($json, true);
        
        if ( $assoc === NULL ) return $response->withStatus( StatusCodeInterface::STATUS_BAD_REQUEST );

        $mesa = MesaModel::crearmesa($assoc);
        $inserted = MesaModel::insertObject($mesa);
        
        if ( !$inserted ) return $response->withStatus( StatusCodeInterface::STATUS_CONFLICT );
        // NO SE INSERTA SÍ, MAL TIPO O MAL SECTOR O USUARIO Y PASS USADOS.

        return $response->withStatus( StatusCodeInterface::STATUS_CREATED );
    }

    public static function delete(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $deleted = MesaModel::deleteById($id);
        $jsonDeleted = json_encode($deleted);

        if ( $deleted === NULL ) return $response->withStatus( StatusCodeInterface::STATUS_NOT_FOUND );
        
        $response->getBody()->write($jsonDeleted);

        return $response->withAddedHeader('Content-Type', 'application/json');
    }

    public static function update(Request $request, Response $response, array $args): Response
    {
        $json = $request->getBody();
        $assoc = json_decode($json, true);

        $mesa = MesaModel::crearMesa($assoc);
        $updated = MesaModel::updateObject($mesa);

        if ( !$updated ) return $response->withStatus( StatusCodeInterface::STATUS_CONFLICT );
        // NO SE INSERTA SÍ, MAL TIPO O MAL SECTOR O USUARIO Y PASS USADOS.

        return $response->withStatus( StatusCodeInterface::STATUS_NO_CONTENT );
    }

    private static function generarCodigo ( int $length = 5 ) : string {
        $numeros = '0123456789';
        $letras = 'abcdefghijklmnñopqrstuvwxyz';
        $chars = $numeros . $letras;
        $charsArr = str_split($chars);
        $ret = '';

        for ( $cantLetrasAgregadas = 0; $cantLetrasAgregadas < $length; $cantLetrasAgregadas++ ) {
            $indexNrs = random_int( 0, count($charsArr) );
            $ret .= $charsArr[$indexNrs];            
        }
        
        return $ret;
    }

}