<?php

namespace Models;

require_once __DIR__ . '/../POPOs/Pedido.php';
require_once __DIR__ . '/CRUDAbstractImplementation.php';

use Models\CRUDAbstractImplementation;
use POPOs\Pedido;

class PedidoModel extends CRUDAbstractImplementation {

    public function __construct()
    {
        parent::__construct( Pedido::class );
    }

    private function __clone()
    {}

}