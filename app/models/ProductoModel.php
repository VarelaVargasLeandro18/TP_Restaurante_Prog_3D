<?php

namespace Models;

require_once __DIR__ . '/../POPOs/Producto.php';
require_once __DIR__ . '/CRUDAbstractImplementation.php';

use Models\CRUDAbstractImplementation;
use POPOs\Producto;

class ProductoModel extends CRUDAbstractImplementation {

    public function __construct()
    {
        parent::__construct( Producto::class );
    }

    private function __clone()
    {}

}