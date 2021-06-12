<?php

namespace Middleware;

require_once __DIR__ . '/../models/Auth.php';

use Fig\Http\Message\StatusCodeInterface as SCI;
use Models\Auth;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class LogIn {

    public static array $permitidos = array(NULL);

    private static function obtenerTipo ( string $jwt ) : ?int {
        $jsonUsr = Auth::ObtenerDatos( $jwt );
        
        if ( $jsonUsr === NULL ) return -100;

        $parsedUsr = json_decode( $jsonUsr, true );
        return $parsedUsr['tipo']['id'];
    }

    public static function permitirAccesoPorTipo( Request $request, Handler $handler ) : ResponseInterface {

        $jwt = $request->getHeader('Authorization');

        if ( empty($jwt) ) return (new Response())->withStatus( SCI::STATUS_BAD_REQUEST, 'No se especificó un JWT' );
        
        try {
            $sector = self::obtenerTipo($jwt[0]);
        } catch ( \Throwable $ex ) {
            return (new Response())->withStatus( SCI::STATUS_BAD_REQUEST, 'El JWT es equivocado.' );
        }

        if ( $sector === -100 ) return (new Response())->withStatus(SCI::STATUS_FORBIDDEN, 'El usuario y/o contraseña no son correctos.');
        if ( !in_array( $sector, self::$permitidos ) ) return (new Response())->withStatus(SCI::STATUS_UNAUTHORIZED, 'No está autorizado para acceder a este recurso.');
        
        $realResponse = $handler->handle( $request );
        return $realResponse;
    }

}