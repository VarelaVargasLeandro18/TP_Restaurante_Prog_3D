<?php

namespace Controllers;

require_once __DIR__ . '/../models/ProductoModel.php';
require_once __DIR__ . '/CRUDAbstractController.php';

use Models\ProductoModel as PM;

class ProductoController extends CRUDAbstractController {

    protected static string $modelName = PM::class;
    protected static string $nombreClase = 'Producto';
    protected static int $PK_type = 0;

    private function __construct()
    {}

    private function __clone()
    {}

}