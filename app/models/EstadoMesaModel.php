<?php

namespace Models;

use POPOs\EstadoMesa;

require_once __DIR__ . '/../POPOs/EstadoMesa.php';
require_once __DIR__ . '/CRUDAbstractImplementation.php';

class EstadoMesaModel extends CRUDAbstractImplementation {

    public function __construct()
    {
        parent::__construct( EstadoMesa::class );
    }

    private function __clone()
    {}

}