<?php

namespace Models;

require_once __DIR__ . '/CRUDAbstractModel.php';
require_once __DIR__ . '/../POPOs/TipoOperacionPedido.php';

use POPOs\TipoOperacionPedido as TOP;

class TipoOperacionPedidoModel extends CRUDAbstractModel {

    public function __construct()
    {
        parent::__construct(TOP::class);
    }

    private function __clone()
    {}

}