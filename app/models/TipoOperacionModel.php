<?php

namespace Models;

require_once __DIR__ . '/../POPOs/TipoOperacion.php';
require_once __DIR__ . '/CRUDAbstractModel.php';

use POPOs\TipoOperacion as TP;

class TipoOperacionModel extends CRUDAbstractModel {

    public function __construct()
    {
        parent::__construct( TP::class );
    }

    private function __clone()
    {}

}