<?php

namespace Models;

require_once __DIR__ . '/../POPOs/PedidoHistorial.php';
require_once __DIR__ . '/CRUDAbstractModel.php';

use Models\CRUDAbstractModel;
use POPOs\PedidoHistorial;

class PedidoHistorialModel extends CRUDAbstractModel {

    public function __construct()
    {
        parent::__construct( PedidoHistorial::class );
    }

    private function __clone()
    {}

}