<?php

namespace Models;

require_once __DIR__ . '/../POPOs/PedidoHistorial.php';
require_once __DIR__ . '/CRUDAbstractImplementation.php';

use Models\CRUDAbstractImplementation;
use POPOs\PedidoHistorial;

class PedidoHistorialModel extends CRUDAbstractImplementation {

    public function __construct()
    {
        parent::__construct( PedidoHistorial::class );
    }

    private function __clone()
    {}

}