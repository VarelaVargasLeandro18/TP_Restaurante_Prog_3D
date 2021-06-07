<?php

namespace Models;

require_once __DIR__ . '/../POPOs/PedidoEstado.php';
require_once __DIR__ . '/CRUDAbstractModel.php';

use Models\CRUDAbstractModel;
use POPOs\PedidoEstado;

class PedidoEstadoModel extends CRUDAbstractModel {

    public function __construct()
    {
        parent::__construct( PedidoEstado::class );
    }

    private function __clone()
    {}

}