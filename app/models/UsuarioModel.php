<?php

namespace Models;

require_once __DIR__ . '/../POPOs/Usuario.php';
require_once __DIR__ . '/CRUDAbstractModel.php';

use Models\CRUDAbstractModel;
use POPOs\Usuario;

class UsuarioModel extends CRUDAbstractModel {

    public function __construct()
    {
        parent::__construct( Usuario::class );
    }

    private function __clone()
    {}

    public function obtenerMedianteUsuarioYContrasenia( string $userName, string $password ) : ?Usuario {

        $ret = NULL;
        $all = (new UsuarioModel())->readAllObjects();

        foreach ( $all as $usr ) {

            $encryptedPass = $usr->getContrasenia();

            if ( 
                $userName === $usr->getUsuario()
                &&
                password_verify( $password, $encryptedPass )
            ) {
                $ret = $usr;
            }

        }

        return $ret;
    }

}