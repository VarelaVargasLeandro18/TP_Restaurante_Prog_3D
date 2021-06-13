<?php

namespace Middleware;

require_once __DIR__ . '/MWUsrDecode.php';

use Fig\Http\Message\StatusCodeInterface as SCI;
use Nyholm\Psr7\ServerRequest;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;


class LogIn extends MWUsrDecode {


    public static function obtenerUsuario( Request $request, Handler $handler ) : Response {

        $jwt = $request->getHeader('Authorization');

        if ( empty($jwt) ) return (new Response())->withStatus( SCI::STATUS_BAD_REQUEST, 'No se especificó un JWT' );
        
        try {
            $usr = parent::decodificarUsuario($jwt[0]);
        } catch ( \Throwable $ex ) {
            return (new Response())->withStatus( SCI::STATUS_BAD_REQUEST, 'El JWT es equivocado, o el usuario y/o contraseña en el lo son.' );
        }

        $originalBody = $request->getBody();
        $originalDecodifiedBody = json_decode( $originalBody, true );
        $nuevoBody = $originalDecodifiedBody;
        $nuevoBody['usuario'] = $usr;
        $nuevoEncodedBody = json_encode( $nuevoBody, JSON_INVALID_UTF8_SUBSTITUTE );
        
        $request = new ServerRequest( $request->getMethod(), $request->getUri(), $request->getHeaders(), $nuevoEncodedBody );

        $realResponse = $handler->handle( $request );

        return $realResponse;
    }
}