<?php

namespace Models;

require_once __DIR__ . '/../POPOs/Comentario.php';
require_once __DIR__ . '/CRUDAbstractModel.php';

use Models\CRUDAbstractModel;
use POPOs\Comentario;

class ComentarioModel extends CRUDAbstractModel {

    public function __construct()
    {
        parent::__construct( Comentario::class );
    }

    private function __clone()
    {}

}