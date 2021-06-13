<?php

namespace Middleware;

require_once __DIR__ . '/MWUsrDecode.php';

use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ServerRequestInterface as Request;
use GuzzleHttp\Psr7\Response;
use Fig\Http\Message\StatusCodeInterface as SCI;

class UsrAuthorizationMW extends MWUsrDecode {

    private static int $codigoSocio = 1;

    public static function permitirSocio ( Request $request, Handler $handler ) {

        $jwt = $request->getHeader('Authorization');

        if ( empty($jwt) ) return (new Response())->withStatus( SCI::STATUS_BAD_REQUEST, 'No se especificó un JWT' );
        
        try {
            $usr = parent::decodificarUsuario($jwt[0]);
        } catch ( \Throwable $ex ) {
            return (new Response())->withStatus( SCI::STATUS_BAD_REQUEST, 'El JWT es equivocado, o el usuario y/o contraseña en el lo son.' );
        }

        if ( $usr['id'] === self::$codigoSocio ) return $handler->handle($request);

        return (new Response())->withStatus(SCI::STATUS_UNAUTHORIZED, 'No autorizado.');
    }

}