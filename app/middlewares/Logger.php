<?php

namespace Middleware;

# POPOs
require_once __DIR__ . '/../POPOs/PedidoHistorial.php';
require_once __DIR__ . '/../POPOs/OperacionHistorial.php';
use POPOs\PedidoHistorial;
use POPOs\OperacionHistorial;

# Models
require_once __DIR__ . '/../models/PedidoHistorialModel.php';
require_once __DIR__ . '/../models/OperacionHistorialModel.php';
require_once __DIR__ . '/../models/TipoOperacionModel.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/SectorOperacionModel.php';
require_once __DIR__ . '/../models/PedidoProductoModel.php';
require_once __DIR__ . '/../models/TipoOperacionPedidoModel.php';
use Models\PedidoHistorialModel;
use Models\TipoOperacionPedidoModel;
use Models\PedidoProductoModel;
use Models\UsuarioModel;
use Models\OperacionHistorialModel;
use Models\TipoOperacionModel;
use Models\SectorOperacionModel;

# Middleware
require_once __DIR__ . '/MWUsrDecode.php';


# Otros
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as IRequest;
use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Server\RequestHandlerInterface as IHandler;
use Fig\Http\Message\StatusCodeInterface as SCI;

class Logger extends MWUsrDecode
{

    private static array $operaciones = array (
        'GET' => 1,
        'POST' => 2,
        'DELETE' => 3,
        'PUT' => 4
    );

    private static int $sectorUsuarios = 1;
    private static int $sectorMesas = 2;
    private static int $sectorPedidos = 3;
    private static int $sectorProductos = 4;

    private static function loggerOperacion ( IRequest $request, IHandler $handler, int $sectorId ) : IResponse {
        $jwt = $request->getHeader('Authorization');

        if ( empty($jwt) ) return (new Response())->withStatus( SCI::STATUS_BAD_REQUEST, 'No se especificó un JWT' );
        
        try {
            $usr = parent::decodificarUsuario($jwt[0]);
        } catch ( \Throwable $ex ) {
            return (new Response())->withStatus( SCI::STATUS_BAD_REQUEST, 'El JWT es equivocado, o el usuario y/o contraseña en el lo son.' );
        }

        $id = $usr['id'];
        $method = $request->getMethod();
        $tipooperacionId = self::$operaciones[$method];

        $ohm = new OperacionHistorialModel();
        $tom = new TipoOperacionModel();
        $um = new UsuarioModel();
        $som = new SectorOperacionModel();
        
        $responsable = $um->readById($id);
        $to = $tom->readById($tipooperacionId);
        $so = $som->readById($sectorId);

        if ( $responsable === NULL ) return (new Response())->withStatus( SCI::STATUS_NOT_FOUND, "No se encontró el usuario especificado en el JWT." );

        $responseReal = $handler->handle($request);

        $codeStatusResponse = $responseReal->getStatusCode();
        $exito = ( $codeStatusResponse >= 200 && $codeStatusResponse < 300 );
        
        $oh = new OperacionHistorial(
            0,
            $to,
            $responsable,
            new \DateTime(),
            $exito,
            $so
        );

        $responsable->incrementarOperaciones();
        
        if ( !$um->updateObject($responsable) || $ohm->insertObject( $oh ) === NULL ) return (new Response())->withStatus( SCI::STATUS_INTERNAL_SERVER_ERROR, 'Se produjo un error al registrar la operación' );

        return $responseReal;
    }

    public static function loggerOperacionUsuario ( IRequest $request, IHandler $handler ) : IResponse {
        return self::loggerOperacion( $request, $handler, self::$sectorUsuarios );
    }

    public static function loggerOperacionMesa ( IRequest $request, IHandler $handler ) : IResponse {
        return self::loggerOperacion( $request, $handler, self::$sectorMesas );
    }

    public static function loggerOperacionPedidos ( IRequest $request, IHandler $handler ) : IResponse {
        return self::loggerOperacion( $request, $handler, self::$sectorPedidos );
    }

    public static function loggerOperacionProductos ( IRequest $request, IHandler $handler ) : IResponse {
        return self::loggerOperacion( $request, $handler, self::$sectorProductos);
    }

    private static int $pedidoCreado = 1;
    private static int $pedidoEnPreparacion = 2;
    private static int $pedidoListo = 3;

    private static function loggerPedidoHistorial( IRequest $request, IHandler $handler, int $operacion, int $idPP, bool $handle ) : ?IResponse {
        $jwt = $request->getHeader('Authorization');

        if ( empty($jwt) ) return (new Response())->withStatus( SCI::STATUS_BAD_REQUEST, 'No se especificó un JWT' );
        
        try {
            $usr = parent::decodificarUsuario($jwt[0]);
        } catch ( \Throwable $ex ) {
            return (new Response())->withStatus( SCI::STATUS_BAD_REQUEST, 'El JWT es equivocado, o el usuario y/o contraseña en el lo son.' );
        }

        $idusr = $usr['id'];
        $um = new UsuarioModel();
        $responsable = $um->readById($idusr);
        
        $ppm = new PedidoProductoModel();
        $pp = $ppm->readById($idPP);

        $topm = new TipoOperacionPedidoModel();
        $top = $topm->readById($operacion);
        
        $hm = new PedidoHistorialModel();
        $historial = new PedidoHistorial(
            0,
            $responsable,
            $pp,
            $top,
            new \DateTime()
        );

        $hm->insertObject($historial);

        if ( $handle) return $handler->handle($request);   
        return NULL;     
    }

    public static function loggerPedidoCreado ( IRequest $request, IHandler $handler ) : IResponse {
        $response = $handler->handle($request);
        $status = $response->getStatusCode();

        if ( !($status >= 200 && $status < 300) ) return $response;

        $id = json_decode($response->getBody()->__toString(), true)['id'];

        self::loggerPedidoHistorial( $request, $handler, self::$pedidoCreado, $id, false );
        
        return $response;
    }

    public static function loggerPedidoEnPreparacion ( IRequest $request, IHandler $handler ) : IResponse {
        $response = $handler->handle($request);
        $status = $response->getStatusCode();

        if ( !($status >= 200 && $status < 300) ) return $response;

        $route = $request->getRequestTarget();
        $id = substr($route, strrpos($route, '/') + 1);
        $id = intval($id);

        self::loggerPedidoHistorial( $request, $handler, self::$pedidoEnPreparacion, $id, false );
        
        return $response;
    }

    public static function loggerPedidoListoParaServir ( IRequest $request, IHandler $handler ) : IResponse {
        $response = $handler->handle($request);
        $status = $response->getStatusCode();

        if ( !($status >= 200 && $status < 300) ) return $response;

        $route = $request->getRequestTarget();
        $id = substr($route, strrpos($route, '/') + 1);
        $id = intval($id);

        self::loggerPedidoHistorial( $request, $handler, self::$pedidoListo, $id, false );
        
        return $response;
    }

}