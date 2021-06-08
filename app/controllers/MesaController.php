<?php

namespace Controllers;

require_once __DIR__ . '/CRUDAbstractController.php';
require_once __DIR__ . '/../models/MesaModel.php';
require_once __DIR__ . '/../POPOs/Mesa.php';
require_once __DIR__ . '/../POPOs/EstadoMesa.php';

use Models\MesaModel as MM;
use POPOs\Mesa as M;

/*
    "id": "aaaaa"
*/

class MesaController extends CRUDAbstractController {

    protected static string $modelName = MM::class;
    protected static string $nombreClase = M::class;
    protected static int $PK_type = 1;
    protected static ?array $jsonConfig = array (
        'id' => NULL
    );

    protected static function createObject(array $array): mixed
    {
        return new M (
            $array['id'],
            NULL
        );
    }

    protected static function updateObject(array $array, mixed $objBD): mixed
    {
        $objBD->setId( $array['id'] );
    }

}