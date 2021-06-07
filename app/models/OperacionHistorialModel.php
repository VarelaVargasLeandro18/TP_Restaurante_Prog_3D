<?php

namespace Models;

require_once __DIR__ . '/../POPOs/OperacionHistorial.php';
require_once __DIR__ . '/CRUDAbstractModel.php';

use Models\CRUDAbstractModel;
use POPOs\OperacionHistorial;

class OperacionHistorialModel extends CRUDAbstractModel {

    public function __construct()
    {
        parent::__construct( OperacionHistorial::class );
    }

    private function __clone()
    {}

}