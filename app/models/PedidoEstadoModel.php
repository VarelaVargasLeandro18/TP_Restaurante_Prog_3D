<?php

namespace Models;

require_once __DIR__ . '/../POPOs/PedidoEstado.php';
require_once __DIR__ . '/CRUDAbstractImplementation.php';

use Models\CRUDAbstractImplementation;
use POPOs\PedidoEstado;

class PedidoEstadoModel extends CRUDAbstractImplementation {

    public function __construct()
    {
        parent::__construct( PedidoEstado::class );
    }

    private function __clone()
    {}

}