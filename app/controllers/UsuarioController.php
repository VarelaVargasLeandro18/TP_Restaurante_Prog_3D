<?php

namespace Controllers;

require_once __DIR__ . '/CRUDAbstractController.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/TipoUsuarioModel.php';
require_once __DIR__ . '/../POPOs/Usuario.php';

use Psr\Http\Message\ServerRequestInterface as IRequest;
use Psr\Http\Message\ResponseInterface as IResponse;
use Fig\Http\Message\StatusCodeInterface as SCI;

use Models\UsuarioModel as UM;
use Models\TipoUsuarioModel as TUM;
use POPOs\Usuario as U;

/*
    "nombre": "",
    "apellido": "",
    "usuario": "",
    "contrasenia": ""
*/

class UsuarioController extends CRUDAbstractController {

    protected static string $modelName = UM::class;
    protected static string $nombreClase = 'Usuario';
    protected static int $PK_type = 1;
    protected static ?array $jsonConfig = array (
        "nombre" => '',
        "apellido" => '', 
        "usuario" => '',
        "contrasenia" => ''
    );

    protected static function createObject(array $array): mixed
    {
        $tipom = new TUM();
        $tipo = $tipom->readById( 6 );

        return (new U(
            0,
            $array['nombre'],
            $array['apellido'],
            $tipo,
            $array['usuario'],
        ))  
            ->setContrasenia( $array['contrasenia'] )
            ->setFechaIngreso( new \DateTime() );
    }

    protected static function updateObject(array $array, mixed $objBD): mixed
    {
        $obj = self::createObject($array);

        $objBD->setNombre( $obj->getNombre() );
        $objBD->setApellido ( $obj->getApellido() );
        $objBD->setTipo ( $obj->getTipo() );
        $objBD->setUsuario ( $obj->getUsuario() );
        $objBD->setContrasenia( $array['contrasenia'] );

        return $objBD;
    }

    private static function cambiarEstadoEmpleado ( IRequest $request, IResponse $response, array $args, bool $estado ) : IResponse {
        $id = intval($args['id']);
        $um = new UM();
        
        $usr = $um->readById($id);

        if ( $usr === NULL ) return $response->withStatus( SCI::STATUS_NOT_FOUND, 'No se encontró el ' . self::$nombreClase . ' especificado.' );

        $usr->setSuspendido($estado);

        if ( !$um->updateObject($usr) ) return $response->withStatus( SCI::STATUS_INTERNAL_SERVER_ERROR, 'Error al realizar el update del ' . self::$nombreClase );

        return $response->withStatus( SCI::STATUS_NO_CONTENT, 'Se realizó correctamente el update de ' . $usr->getNombre() );
    }

    public static function suspenderEmpleado ( IRequest $response, IResponse $request, array $args ) : IResponse {
        return self::cambiarEstadoEmpleado( $response, $request, $args, true );
    }

    public static function continuarEmpleado ( IRequest $response, IResponse $request, array $args ) : IResponse {
        return self::cambiarEstadoEmpleado( $response, $request, $args, false );
    }

}