<?php

namespace Controllers;

require_once __DIR__ . '/../interfaces/IGetAuthApi.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/Auth.php';

use Fig\Http\Message\StatusCodeInterface as SCI;
use interfaces\IGetAuthApi;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Models\UsuarioModel as UM;
use Models\Auth;

class AuthController implements IGetAuthApi {

    public static function getToken(Request $request, Response $response, array $args): Response
    {
        $parsedRequest = json_decode( $request->getBody()->getContents(), true );

        if ( !key_exists( 'usuario', $parsedRequest ) || !key_exists( 'contrasenia', $parsedRequest ) ) return $response->withStatus( SCI::STATUS_BAD_REQUEST, 'El json enviado es incorrecto.' );

        $um = new UM();
        $usr = $um->obtenerMedianteUsuarioYContrasenia( $parsedRequest['usuario'], $parsedRequest['contrasenia'] );

        if ( $usr === NULL ) return $response->withStatus( SCI::STATUS_NOT_FOUND, 'No se encontró el usuario.' );

        $jsonUsuario = json_encode( $usr, JSON_INVALID_UTF8_SUBSTITUTE );
        
        $token = Auth::ObtenerToken($jsonUsuario);
        
        $response->getBody()->write($token);
        return $response;
    }
}