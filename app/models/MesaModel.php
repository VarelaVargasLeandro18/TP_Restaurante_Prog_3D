<?php

namespace Models;

use POPOs\Mesa;

require_once __DIR__ . '/../POPOs/Mesa.php';
require_once __DIR__ . '/CRUDAbstractModel.php';

class MesaModel extends CRUDAbstractModel {

    public function __construct()
    {
        parent::__construct( Mesa::class );
    }

    private function __clone()
    {}

}