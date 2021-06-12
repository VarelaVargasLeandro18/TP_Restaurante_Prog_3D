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
    protected static string $nombreClase = '';
    /**
     * Define el tipo de 'primary key', siendo:
     * 0: int
     * !=0: string
     * Por defecto su valor es 0.
     */
    protected static int $PK_type = 0;
    /**
     * Define cómo debe ser el JSON que se va a recibir. Deberá ser un array asociativo que tendrá el 'callable' de cómo se convertirá el valor que se encuentre en esa posición.
     * Ej: 'id' => intval
     * @var array key => callable
     */
    protected static ?array $jsonConfig = NULL;

    private static ?ICRUD $cai = NULL;

    private function __construct() {}
    private function __clone() {}

   protected static abstract function createObject( array $array ) : mixed;
    protected static abstract function updateObject ( array $array, mixed $objBD ) : mixed;

    /**
     * Chequea que el JSON proporcionado en el request sea correcto.
     */
    private static function checkJson ( array $decodedJson ) : bool {
        return empty( array_diff_key( $decodedJson, static::$jsonConfig ) );
    }

    private static function createICRUD () {
        if ( self::$cai === NULL ){

            if ( !class_exists(static::$modelName) ) throw new \Exception( "No existe la clase", -458 );
        
            self::$cai = new static::$modelName;
        }
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
        } catch ( \Throwable $ex ) {
            return new $response->withStatus( SCI::STATUS_INTERNAL_SERVER_ERROR );
        }

        $id = -1;

        if ( static::$PK_type == 0 ) 
            $id = intval( $args['id'] );
        else if ( static::$PK_type !== 0 ) 
            $id = $args['id'];

        $object = static::$cai->readById( $id );

        if ( $object === NULL ) 
            return $response->withStatus( SCI::STATUS_NOT_FOUND, 'No se encontró el ' . static::$nombreClase . ' con el id ' . $id );

        return self::procesarRespuesta(
            $response,
            $object,
            SCI::STATUS_OK
        );
    }

    public static function readAll(Request $request, Response $response, array $args): Response
    {
        self::createICRUD();

        $arrayObjects = static::$cai->readAllObjects();

        if ( $arrayObjects === NULL ) return $response->withStatus( SCI::STATUS_INTERNAL_SERVER_ERROR );

        if ( empty( $arrayObjects ) ) return $response->withStatus( SCI::STATUS_NOT_FOUND, 'No hay ningún ' . static::$nombreClase . '.' );
        
        return self::procesarRespuesta(
            $response,
            $arrayObjects,
            SCI::STATUS_OK
        );
    }

    public static function insert(Request $request, Response $response, array $args): Response
    {
        try {
            self::createICRUD();
        } catch ( \Throwable $ex ) {
            return new $response->withStatus( SCI::STATUS_INTERNAL_SERVER_ERROR );
        }

        $jsonObject = $request->getBody()->getContents();
        $decodedAssoc = json_decode ( $jsonObject, true, 512, JSON_INVALID_UTF8_SUBSTITUTE );
       
        if ( !self::checkJson($decodedAssoc) ) return $response->withStatus( SCI::STATUS_BAD_REQUEST, 'El JSON proporcionado no es válido.' );

        $obj = static::createObject( $decodedAssoc );
        
        if ( !static::$cai->insertObject( $obj ) ) return $response->withStatus( SCI::STATUS_INTERNAL_SERVER_ERROR, 'No pudo guardarse el ' . static::$nombreClase );

        return $response->withStatus( SCI::STATUS_CREATED );
    }

    public static function delete(Request $request, Response $response, array $args): Response
    {
        try {
            self::createICRUD();
        } catch ( \Throwable $ex ) {
            return new $response->withStatus( SCI::STATUS_INTERNAL_SERVER_ERROR );
        }

        $id = -1;

        if ( static::$PK_type == 0 ) 
            $id = intval( $args['id'] );
        else if ( static::$PK_type !== 0 ) 
            $id = $args['id'];
        
        $deleted = static::$cai->deleteById($id);

        if ( $deleted === NULL ) return $response->withStatus( SCI::STATUS_INTERNAL_SERVER_ERROR, 'No pudo borrarse el ' . static::$nombreClase );

        return self::procesarRespuesta(
            $response,
            $deleted,
            SCI::STATUS_OK
        );
    }

    public static function update(Request $request, Response $response, array $args): Response
    {
        try {
            self::createICRUD();
        } catch ( \Throwable $ex ) {
            return new $response->withStatus( SCI::STATUS_INTERNAL_SERVER_ERROR );
        }

        $id = -1;

        if ( static::$PK_type == 0 ) 
            $id = intval( $args['id'] );
        else if ( static::$PK_type !== 0 ) 
            $id = $args['id'];

        $jsonObject = $request->getBody()->getContents();
        $decodedAssoc = json_decode ( $jsonObject, true, 512, JSON_INVALID_UTF8_SUBSTITUTE );
        
        if ( !self::checkJson($decodedAssoc) ) return $response->withStatus( SCI::STATUS_BAD_REQUEST, 'El JSON proporcionado no es válido.' );

        $bdObject = self::$cai->readById($id);

        if ( !$bdObject ) return $response->withStatus( SCI::STATUS_NOT_FOUND, 'No se encontró el ' . static::$nombreClase . ' con el id ' . $id );
        
        if ( ($bdObject = static::updateObject( $decodedAssoc, $bdObject )) === false ) return $response->withStatus( SCI::STATUS_INTERNAL_SERVER_ERROR, 'No se pudo realizar un update del ' . static::$nombreClase . ' con id ' . $id );        
        
        if ( !self::$cai->updateObject($bdObject) ) return $response->withStatus( SCI::STATUS_INTERNAL_SERVER_ERROR, 'No se pudo realizar un update del ' . static::$nombreClase . ' con id ' . $id );

        return $response->withStatus( SCI::STATUS_OK );
    }

}