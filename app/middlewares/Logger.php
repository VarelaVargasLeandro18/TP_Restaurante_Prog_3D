<?php
require_once __DIR__ . '/../models/Auth.php';

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class Logger
{
    private static function loguear ( Request $request, RequestHandler $handler, callable $func ) : Response {
        $response = new Response();
        $requestHeaders = $request->getHeaders();

        if ( !array_key_exists( 'Authorization', $requestHeaders ) ) return $response->withStatus( StatusCodeInterface::STATUS_FORBIDDEN );

        $jwt = $requestHeaders['Authorization'][0];
        
        if ( Auth::Verificar($jwt) === false ) return $response->withStatus( StatusCodeInterface::STATUS_FORBIDDEN );
        
        if ( $func(Auth::ObtenerDatos($jwt)) === false ) return $response->withStatus( StatusCodeInterface::STATUS_FORBIDDEN );

        $response = $handler->handle($request);

        return $response;
    }


    public static function logSocio ( Request $request, RequestHandler $handler ) : Response {
        return self::loguear ( $request, $handler, function ( stdClass $empleado ) {
            return $empleado->tipo->tipo === '#socio';
        } );
    }

}