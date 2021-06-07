<?php

namespace Models;

require_once __DIR__ . '/../POPOs/Factura.php';
require_once __DIR__ . '/CRUDAbstractModel.php';

use Models\CRUDAbstractModel;
use POPOs\Factura;

class FacturaModel extends CRUDAbstractModel {

    public function __construct()
    {
        parent::__construct( Factura::class );
    }

    private function __clone()
    {}

}