<?php

namespace Controllers;

require_once __DIR__ . '/CRUDAbstractController.php';
require_once __DIR__ . '/../models/MesaModel.php';
require_once __DIR__ . '/../models/EstadoMesaModel.php';
require_once __DIR__ . '/../POPOs/Mesa.php';

use Models\MesaModel as MM;
use Models\EstadoMesaModel as EM;
use POPOs\Mesa as M;

use Psr\Http\Message\ServerRequestInterface as IRequest;
use Psr\Http\Message\ResponseInterface as IResponse;
use Fig\Http\Message\StatusCodeInterface as SCI;
use GuzzleHttp\Psr7\Response;

/*
    "id": "aaaaa"
*/

class MesaController extends CRUDAbstractController {

    protected static string $modelName = MM::class;
    protected static string $nombreClase = 'Mesa';
    protected static int $PK_type = 1;
    protected static ?array $jsonConfig = array (
        'id' => NULL
    );
    
    private static int $tipoUsuarioAdmin = 1;
    private static int $estadoMesaDefecto = 1;
    private static int $estadoMesaEspera = 2;
    private static int $estadoMesaComiendo = 3;
    private static int $estadoMesaPagando = 4;

    protected static function createObject(array $array): mixed
    {
        $em = new EM();
        $estado = $em->readById( self::$estadoMesaDefecto );
        return new M (
            $array['id'],
            $estado
        );
    }

    protected static function updateObject(array $array, mixed $objBD): mixed
    {
        $objBD->setId( $array['id'] );
        return $objBD;
    }

    private static function cambiarEstadoMesa ( IRequest $request, IResponse $response, array $args, int $nuevoEstado ) : IResponse {
        $usr = json_decode( $request->getBody()->__toString(), true )['usuario'];

        if ( $nuevoEstado === self::$estadoMesaDefecto && $usr['tipo']['id'] !== self::$tipoUsuarioAdmin ) return $response->withStatus( SCI::STATUS_UNAUTHORIZED, 'Solo los socios pueden cambiar la mesa a cerrada.' ); 

        $idMesa = $args['codigo'];
        $mm = new MM();
        $emm = new EM();
        $mesa = $mm->readById( $idMesa );
        $em = $emm->readById( $nuevoEstado );

        if ( $mesa === NULL ) return $response->withStatus( SCI::STATUS_NOT_FOUND, 'No se encontró el ' . self::$nombreClase . ' especificado.' );
        if ( $em === NULL ) return $response->withStatus( SCI::STATUS_NOT_FOUND, 'No se encontró el estado mesa correspondiente.' );
        
        $mesa->setEstado($em);
        
        if ( !$mm->updateObject($mesa) ) return $response->withStatus( SCI::STATUS_INTERNAL_SERVER_ERROR, 'No se pudo actualizar la mesa.' );

        return $response->withStatus( SCI::STATUS_NO_CONTENT, 'Se ha actualizado la mesa exitosamente.' );
    }

    public static function cambiarACerrada ( IRequest $request, IResponse $response, array $args ) : IResponse {
        return self::cambiarEstadoMesa( $request, $response, $args, self::$estadoMesaDefecto );
    }

    public static function cambiarAEspera ( IRequest $request, IResponse $response, array $args ) : IResponse {
        return self::cambiarEstadoMesa( $request, $response, $args, self::$estadoMesaEspera );
    }

    public static function cambiarAComiendo ( IRequest $request, IResponse $response, array $args ) : IResponse {
        return self::cambiarEstadoMesa( $request, $response, $args, self::$estadoMesaComiendo );
    }

    public static function cambiarAPagando ( IRequest $request, IResponse $response, array $args ) : IResponse {
        return self::cambiarEstadoMesa( $request, $response, $args, self::$estadoMesaPagando );
    }

}