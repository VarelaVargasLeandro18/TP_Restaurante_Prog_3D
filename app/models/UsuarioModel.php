<?php

namespace Models;

require_once __DIR__ . '/../POPOs/Usuario.php';
require_once __DIR__ . '/CRUDAbstractImplementation.php';

use Models\CRUDAbstractImplementation;
use POPOs\Usuario;

class UsuarioModel extends CRUDAbstractImplementation {

    public function __construct()
    {
        parent::__construct( Usuario::class );
    }

    private function __clone()
    {}

}