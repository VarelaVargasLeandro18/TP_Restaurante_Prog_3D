<?php

namespace Controllers;

require_once __DIR__ . '/../interfaces/ICRUDApi.php';
require_once __DIR__ . '/../interfaces/ICRUD.php';

use Fig\Http\Message\StatusCodeInterface as SCI;
use interfaces\ICRUD;
use interfaces\ICRUDApi;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

abstract class CRUDAbstractController implements ICRUDApi {

    protected static string $modelName = '';
    private static ?ICRUD $cai = NULL;
    protected static string $nombreClase = '';

    /**
     * Define el tipo de 'primary key', siendo:
     * 0: int
     * !=0: string
     * Por defecto su valor es 0.
     */
    protected static int $PK_type = 0;

    private function __construct() {}
    private function __clone() {}

    private static function createICRUD () {
        if ( self::$cai === NULL )
            self::$cai = new static::$modelName;
    }

    private static function procesarRespuesta ( Response $response, mixed $object, int $okayCode ) : Response {

        $jsonEncoded = json_encode( $object, JSON_INVALID_UTF8_SUBSTITUTE );

        if ( $jsonEncoded === false ) return $response->withStatus( SCI::STATUS_INTERNAL_SERVER_ERROR );

        $response->getBody()->write( $jsonEncoded );

        return $response->withStatus( $okayCode )->withAddedHeader( 'Content-Type', 'application/json' );
    }

    public static function read ( Request $request, Response $response, array $args ): Response
    {
        try {
            self::createICRUD();
        } catch ( \Throwable ) {
            return new $response->withStatus( SCI::STATUS_INTERNAL_SERVER_ERROR );
        }

        $id = -1;

        if ( static::$PK_type == 0 ) 
            $id = intval( $args['id'] );
        else if ( static::$PK_type !== 0 ) 
            $id = $args['id'];

        $object = static::$cai->readById( $id );

        if ( $object === NULL ) 
            return $response->withStatus( SCI::STATUS_NOT_FOUND );

        return self::procesarRespuesta(
            $response,
            $object,
            SCI::STATUS_OK
        );
    }

    public static function readAll(Request $request, Response $response, array $args): Response
    {
        try {
            self::createICRUD();
        } catch ( \Throwable ) {
            return new $response->withStatus( SCI::STATUS_INTERNAL_SERVER_ERROR );
        }

        $arrayObjects = static::$cai->readAllObjects();

        if ( $arrayObjects === NULL )
            return $response->withStatus( SCI::STATUS_INTERNAL_SERVER_ERROR );

        if ( empty( $arrayObjects ) )
            return $response->withStatus( SCI::STATUS_NOT_FOUND, 'No hay ningún ' . self::$nombreClase . '.' );
        
        return self::procesarRespuesta(
            $response,
            $arrayObjects,
            SCI::STATUS_OK
        );
    }

    public static function insert(Request $request, Response $response, array $args): Response
    {
        return $response;
    }

    public static function delete(Request $request, Response $response, array $args): Response
    {
        return $response;
    }

    public static function update(Request $request, Response $response, array $args): Response
    {
        return $response;
    }

}