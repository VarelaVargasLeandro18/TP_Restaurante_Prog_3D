<?php

require_once __DIR__ . '/../interfaces/IGetAuth.php';
require_once __DIR__ . '/../models/Auth.php';
require_once __DIR__ . '/../models/EmpleadoModel.php';
require_once __DIR__ . '/../beans/Empleado.php';

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class AuthController implements IGetAuth {

    public static function getToken(Request $request, Response $response, array $args): Response
    {
        $datosUsuarioJSON = $request->getBody();
        $datosUsuario = json_decode($datosUsuarioJSON, true);

        if ( $datosUsuario === NULL ) return $response->withStatus( StatusCodeInterface::STATUS_BAD_REQUEST );

        $nombreUsr = $datosUsuario['usuario'];
        $ctrUsr = $datosUsuario['contrasenia'];

        $usuario = EmpleadoModel::checkUsrPassAll($nombreUsr, $ctrUsr);

        if ( $usuario === NULL ) return $response->withStatus( StatusCodeInterface::STATUS_NOT_FOUND );

        $response->getBody()->write(Auth::ObtenerToken( $usuario ));
        
        return $response;
    }

}