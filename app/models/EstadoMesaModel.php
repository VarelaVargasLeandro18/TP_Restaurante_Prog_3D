<?php

namespace Models;

use POPOs\EstadoMesa;

require_once __DIR__ . '/../POPOs/EstadoMesa.php';
require_once __DIR__ . '/CRUDAbstractModel.php';

class EstadoMesaModel extends CRUDAbstractModel {

    public function __construct()
    {
        parent::__construct( EstadoMesa::class );
    }

    private function __clone()
    {}

}