<?php

namespace Models;

require_once __DIR__ . '/../POPOs/TipoComentario.php';
require_once __DIR__ . '/CRUDAbstractModel.php';

use Models\CRUDAbstractModel;
use POPOs\TipoComentario;

class TipoComentarioModel extends CRUDAbstractModel {

    public function __construct()
    {
        parent::__construct( TipoComentario::class );
    }

    private function __clone()
    {}

}