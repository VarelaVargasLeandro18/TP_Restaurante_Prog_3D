<?php

namespace Controllers;

require_once __DIR__ . '/../models/PedidoModel.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/ProductoModel.php';
require_once __DIR__ . '/../models/MesaModel.php';
require_once __DIR__ . '/../models/PedidoEstadoModel.php';
require_once __DIR__ . '/../db/DoctrineEntityManagerFactory.php';

require_once __DIR__ . '/CRUDAbstractController.php';

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Models\PedidoModel as PM;
use Models\UsuarioModel as UM;
use Models\ProductoModel as ProdM;
use Models\MesaModel as MM;
use Models\PedidoEstadoModel as PEM;

use POPOs\Pedido as P;

use db\DoctrineEntityManagerFactory as DEMF;
use Models\PedidoModel;
use POPOs\Mesa;

/*  Id autogenerado, codigo autogenerado
    ?'codigo'
    'cantidad': -1,
    'IdCliente': -1,
    'IdProducto': -1,
    'IdMesa': 'aaaa0'
*/

class PedidoController extends CRUDAbstractController {

    protected static string $modelName = PM::class;
    protected static string $nombreClase = 'Pedido';
    protected static int $PK_type = 0;

    protected static ?array $jsonConfig = array( 
        'cantidad' => '', 
        'IdCliente' => '',
        'IdProducto' => '',
        'IdMesa' => ''
    );

    private function __construct()
    {}

    private function __clone()
    {}

    private static function generateRandomString($length = 5) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private static function checkCodigo(string $codigo) : bool {

        $queryBuilder = DEMF::getQueryBuilder();

        $queryBuilder->select('p.id')
                        ->from('POPOs\Pedido', 'p')
                        ->where('p.codigo = :codigo')
                        ->setParameter(':codigo', $codigo);
        $obj = $queryBuilder->getQuery()->execute();
        
        return empty($obj);
    }

    private static function generarCodigo() : string {
        do {
            $codigo = self::generateRandomString();
        } while ( !self::checkCodigo($codigo) );
        return $codigo;
    }
    
    protected static function createObject(array $array): mixed
    {
        $codigo = self::generarCodigo();
        $usuariom = new UM();
        $produdctom = new ProdM();
        $mesam = new MM();
        $pedidoestadom = new PEM();

        $cliente = $usuariom->readById( $array['IdCliente'] );
        $producto = $produdctom->readById( $array['IdProducto'] );
        $mesa = $mesam->readById( $array['IdMesa'] );
        $estado = $pedidoestadom->readById(1);

        return new P(
            0,
            $codigo,
            $array['cantidad'],
            $cliente,
            NULL,
            $producto,
            $mesa,
            $estado
        );
    }

    protected static function updateObject(array $array, mixed $objBD): mixed
    {
        $objT = self::createObject($array);
        $objBD->setCantidad($objT->getCantidad());
        $objBD->setCliente($objT->getCliente());
        $objBD->setProducto($objT->getProducto());
        $objBD->setMesa($objT->getMesa());
        $objBD->setEstado($objT->getEstado());
        return $objBD;
    }

}