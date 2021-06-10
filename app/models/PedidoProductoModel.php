<?php

namespace Models;

require_once __DIR__ . '/CRUDAbstractModel.php';
require_once __DIR__ . '/../POPOs/PedidoProducto.php';

use POPOs\PedidoProducto as PP;

class PedidoProductoModel extends CRUDAbstractModel {

    public function __construct()
    {
        parent::__construct(PP::class);
    }

    private function __clone()
    {}

}