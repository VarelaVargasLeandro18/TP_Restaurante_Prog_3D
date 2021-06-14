<?php

namespace Models;

require_once __DIR__ . '/../POPOs/SectorOperacion.php';
require_once __DIR__ . '/CRUDAbstractModel.php';

use Models\CRUDAbstractModel;
use POPOs\SectorOperacion;

class SectorOperacionModel extends CRUDAbstractModel {

    public function __construct()
    {
        parent::__construct( SectorOperacion::class );
    }

    private function __clone()
    {}

}