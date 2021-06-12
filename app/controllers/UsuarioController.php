<?php

namespace Controllers;

require_once __DIR__ . '/CRUDAbstractController.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/TipoUsuarioModel.php';
require_once __DIR__ . '/../POPOs/Usuario.php';

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

}