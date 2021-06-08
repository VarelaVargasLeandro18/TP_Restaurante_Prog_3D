<?php

namespace Controllers;

require_once __DIR__ . '/../models/ProductoModel.php';
require_once __DIR__ . '/../models/SectorModel.php';
require_once __DIR__ . '/CRUDAbstractController.php';

use Models\ProductoModel as PM;
use Models\SectorModel as SM;
use POPOs\Producto;
use POPOs\Sector;

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

    private static array $assocArrayExample = array( 
        'nombre' => '', 
        'tipo' => '', 
        'IdSector' => '', 
        'valor' => '' 
    );

    private function __construct()
    {}

    private function __clone()
    {}
    
    protected static function validarObjeto(array $decodedAssoc): Producto
    {
        if ( !empty( array_diff_key( $decodedAssoc, self::$assocArrayExample ) ) ) return false;
        $sm = new SM();

        $IdSector = intval($decodedAssoc['IdSector']);
        $sector = $sm->readById($IdSector);

        if ( !$sector ) return false;

        $valor = floatval($decodedAssoc['valor']);

        return new Producto(
            -1,
            $decodedAssoc['nombre'],
            $decodedAssoc['tipo'],
            $sector,
            $valor
        );
    }

    protected static function updateObjeto(array $decodedAssoc, mixed $objBD): bool
    {
        $productoNuevo = self::validarObjeto( $decodedAssoc );

        if ( !$productoNuevo ) return false;

        $sector = $productoNuevo->getSector();
        $nombre = $productoNuevo->getNombre();
        $tipo = $productoNuevo->getTipo();
        $valor = $productoNuevo->getValor();

        $objBD->setSector($sector);
        $objBD->setNombre($nombre);
        $objBD->setTipo($tipo);
        $objBD->setValor($valor);

        $sm = new SM();
        
        return $sm->updateObject( $objBD );
    }

}