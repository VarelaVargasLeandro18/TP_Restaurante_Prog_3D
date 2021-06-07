<?php

namespace Models;

require_once __DIR__ . '/../POPOs/Comentario.php';
require_once __DIR__ . '/CRUDAbstractImplementation.php';

use Models\CRUDAbstractImplementation;
use POPOs\Comentario;

class ComentarioModel extends CRUDAbstractImplementation {

    public function __construct()
    {
        parent::__construct( Comentario::class );
    }

    private function __clone()
    {}

}