<?php

namespace Middleware;

require_once __DIR__ . '/MWUsrDecode.php';

use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ServerRequestInterface as Request;
use GuzzleHttp\Psr7\Response;
use Fig\Http\Message\StatusCodeInterface as SCI;
use Psr\Http\Message\ResponseInterface;

class UsrAuthorizationMW extends MWUsrDecode {

    private static int $codigoSocio = 1;
    private static int $codigoMozo = 5;
    private static int $codigoCliente = 6;

    private static function permitir ( Request $request, Handler $handler, array $permitidos ) : ResponseInterface {

        $jwt = $request->getHeader('Authorization');

        if ( empty($jwt) ) return (new Response())->withStatus( SCI::STATUS_BAD_REQUEST, 'No se especificó un JWT' );
        
        try {
            $usr = parent::decodificarUsuario($jwt[0]);
        } catch ( \Throwable $ex ) {
            return (new Response())->withStatus( SCI::STATUS_BAD_REQUEST, 'El JWT es equivocado, o el usuario y/o contraseña en el lo son.' );
        }

        if ( !in_array( $usr['tipo']['id'], $permitidos ) ) return (new Response())->withStatus(SCI::STATUS_UNAUTHORIZED, 'No autorizado.');

        return $handler->handle($request);
    }

    public static function permitirSocio ( Request $request, Handler $handler ) : ResponseInterface {
        return self::permitir( $request, $handler, array( self::$codigoSocio ) );
    }

    public static function permitirSocioYMozo ( Request $request, Handler $handler ) : ResponseInterface {
        return self::permitir( $request, $handler, array( self::$codigoSocio, self::$codigoMozo ) );
    }

    public static function restringirCliente ( Request $request, Handler $handler ) : ResponseInterface {

        $jwt = $request->getHeader('Authorization');

        if ( empty($jwt) ) return (new Response())->withStatus( SCI::STATUS_BAD_REQUEST, 'No se especificó un JWT' );
        
        try {
            $usr = parent::decodificarUsuario($jwt[0]);
        } catch ( \Throwable $ex ) {
            return (new Response())->withStatus( SCI::STATUS_BAD_REQUEST, 'El JWT es equivocado, o el usuario y/o contraseña en el lo son.' );
        }

        if ( $usr['id'] === self::$codigoCliente ) return (new Response())->withStatus(SCI::STATUS_UNAUTHORIZED, 'No autorizado.');

        return $handler->handle($request);
    }

}