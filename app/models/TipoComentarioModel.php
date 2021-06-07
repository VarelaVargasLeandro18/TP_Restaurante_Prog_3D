<?php

namespace Models;

require_once __DIR__ . '/../POPOs/TipoComentario.php';
require_once __DIR__ . '/CRUDAbstractImplementation.php';

use Models\CRUDAbstractImplementation;
use POPOs\TipoComentario;

class TipoComentarioModel extends CRUDAbstractImplementation {

    public function __construct()
    {
        parent::__construct( TipoComentario::class );
    }

    private function __clone()
    {}

}