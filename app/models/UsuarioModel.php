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

    public static function obtenerMedianteUsuarioYContrasenia( string $userName, string $password ) {

        $all = parent::readAllObjects();

        foreach ( $all as $key => $usr ) {

            $encryptedPass = $usr->getContrasenia();

        }

    }

}