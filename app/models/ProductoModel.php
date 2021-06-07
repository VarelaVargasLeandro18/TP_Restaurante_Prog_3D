<?php

namespace Models;

require_once __DIR__ . '/../POPOs/Producto.php';
require_once __DIR__ . '/CRUDAbstractModel.php';

use Models\CRUDAbstractModel;
use POPOs\Producto;

class ProductoModel extends CRUDAbstractModel {

    public function __construct()
    {
        parent::__construct( Producto::class );
    }

    private function __clone()
    {}

}