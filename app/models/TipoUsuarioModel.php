<?php

namespace Models;

require_once __DIR__ . '/../POPOs/TipoUsuario.php';
require_once __DIR__ . '/CRUDAbstractModel.php';

use Models\CRUDAbstractModel;
use POPOs\TipoUsuario;

class TipoUsuarioModel extends CRUDAbstractModel {

    public function __construct()
    {
        parent::__construct( TipoUsuario::class );
    }

    private function __clone()
    {}

}
