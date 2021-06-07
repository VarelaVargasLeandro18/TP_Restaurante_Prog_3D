<?php

namespace Models;

require_once __DIR__ . '/../POPOs/TipoUsuario.php';
require_once __DIR__ . '/CRUDAbstractImplementation.php';

use Models\CRUDAbstractImplementation;
use POPOs\TipoUsuario;

class TipoUsuarioModel extends CRUDAbstractImplementation {

    public function __construct()
    {
        parent::__construct( TipoUsuario::class );
    }

    private function __clone()
    {}

}
