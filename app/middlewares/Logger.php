<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
require_once __DIR__ . '/../models/EmpleadoModel.php';
require_once __DIR__ . '/../models/PermisosEmpleadoSectorModel.php';

class Logger
{
    public static function LogOperacion( Request $request, Response $response, callable $next)
    {
        $params = $request->getParsedBody();
        $usuario = $params['usuario'];
        $contrasenia = $params['contrasenia'];
    }
}