<?php

namespace Controllers;

require_once __DIR__ . '/../models/ProductoModel.php';
require_once __DIR__ . '/../models/SectorModel.php';
require_once __DIR__ . '/CRUDAbstractController.php';

use Models\ProductoModel as PM;
use Models\SectorModel as SM;
use POPOs\Producto as P;
use POPOs\Sector as S;

/*
{
    'nombre': '',
    'tipo': '',
    'IdSector': -1,
    'valor': 0.00
}
*/

class ProductoController extends CRUDAbstractController {

    protected static string $modelName = PM::class;
    protected static string $nombreClase = 'Producto';
    protected static int $PK_type = 0;

    protected static ?array $jsonConfig = array( 
        'nombre' => '', 
        'tipo' => '', 
        'IdSector' => '', 
        'valor' => '' 
    );

    private function __construct()
    {}

    private function __clone()
    {}
    
    protected static function createObject(array $array): mixed
    {
        $sm = new SM();
        $s = $sm->readById($array['IdSector']);

        if ( $s === NULL ) return NULL;

        return new P (
            0,
            $array['nombre'],
            $array['tipo'],
            $s,
            $array['valor']
        );
    }

    protected static function updateObject(array $array, mixed $objBD): mixed
    {
        $sm = new SM();
        $s = $sm->readById($array['IdSector']);

        if ( $s === NULL ) return NULL;

        $objBD->setNombre($array['nombre']);
        $objBD->setTipo($array['tipo']);
        $objBD->setSector($s);
        $objBD->setValor($array['valor']);

        return $objBD;
    }

}