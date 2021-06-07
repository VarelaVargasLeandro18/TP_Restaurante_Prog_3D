<?php

namespace Models;

require_once __DIR__ . '/../POPOs/Pedido.php';
require_once __DIR__ . '/CRUDAbstractModel.php';

use Models\CRUDAbstractModel;
use POPOs\Pedido;

class PedidoModel extends CRUDAbstractModel {

    public function __construct()
    {
        parent::__construct( Pedido::class );
    }

    private function __clone()
    {}

}