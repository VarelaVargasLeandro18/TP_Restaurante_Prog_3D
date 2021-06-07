<?php

namespace Models;

require_once __DIR__ . '/../POPOs/OperacionHistorial.php';
require_once __DIR__ . '/CRUDAbstractImplementation.php';

use Models\CRUDAbstractImplementation;
use POPOs\OperacionHistorial;

class OperacionHistorialModel extends CRUDAbstractImplementation {

    public function __construct()
    {
        parent::__construct( OperacionHistorial::class );
    }

    private function __clone()
    {}

}