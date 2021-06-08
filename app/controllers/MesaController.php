<?php

namespace Controllers;

require_once __DIR__ . '/CRUDAbstractController.php';
require_once __DIR__ . '/../models/MesaModel.php';
require_once __DIR__ . '/../models/EstadoMesaModel.php';
require_once __DIR__ . '/../POPOs/Mesa.php';

use Models\MesaModel as MM;
use Models\EstadoMesaModel as EM;
use POPOs\Mesa as M;

/*
    "id": "aaaaa"
*/

class MesaController extends CRUDAbstractController {

    protected static string $modelName = MM::class;
    protected static string $nombreClase = 'Mesa';
    protected static int $PK_type = 1;
    protected static ?array $jsonConfig = array (
        'id' => NULL,
        'estadoId' => 1
    );

    protected static function createObject(array $array): mixed
    {
        $em = new EM();
        $estado = $em->readById( self::$jsonConfig['estadoId'] );
        return new M (
            $array['id'],
            $estado
        );
    }

    protected static function updateObject(array $array, mixed $objBD): mixed
    {
        $objBD->setId( $array['id'] );
    }

}