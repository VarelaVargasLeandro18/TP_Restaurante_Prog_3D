<?php

namespace Models;

require_once __DIR__ . '/../POPOs/Factura.php';
require_once __DIR__ . '/CRUDAbstractImplementation.php';

use Models\CRUDAbstractImplementation;
use POPOs\Factura;

class FacturaModel extends CRUDAbstractImplementation {

    public function __construct()
    {
        parent::__construct( Factura::class );
    }

    private function __clone()
    {}

}