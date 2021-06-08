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
use Models\UsuarioModel;
use Models\ProductoModel;
use Models\MesaModel;
use Models\PedidoEstadoModel;

use POPOs\Pedido;

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

    private static array $assocArrayExample = array( 
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

    private static function crearObjeto( array $decodedAssoc ) : mixed {
        $usuariom = new UsuarioModel();
        $productom = new ProductoModel();
        $mesam = new MesaModel();
        $pedidoestadom = new PedidoEstadoModel();

        $cantidad = intval($decodedAssoc['cantidad']);
        $idcliente = intval($decodedAssoc['IdCliente']);
        $idProducto = intval($decodedAssoc['IdProducto']);
        $idMesa = $decodedAssoc['IdMesa'];

        $cliente = $usuariom->readById($idcliente);
        $producto = $productom->readById($idProducto);
        $mesa = $mesam->readById($idMesa);
        $estadoInicial = $pedidoestadom->readById(1);

        if ( $cliente === NULL | $producto === NULL | $mesa === NULL ) return false;

        if ( !$decodedAssoc['codigo'] ) {
            do {
                $codigo = self::generateRandomString();
            } while ( self::checkCodigo($codigo) );
        }
        else
            $codigo = $decodedAssoc['codigo'];
        
        return new Pedido( 
            -1,
            $codigo,
            $cantidad,
            $cliente,
            NULL,
            $producto,
            $mesa,
            $estadoInicial
        );
    }
    
    protected static function validarObjeto(array $decodedAssoc): mixed
    {
        if ( !empty(array_diff_key($decodedAssoc, self::$assocArrayExample) ) ) return false;
        return self::crearObjeto($decodedAssoc);
    }   
    
    protected static function updateObjeto(array $decodedAssoc, mixed $objBD): bool
    {
        $obj = self::validarObjeto( $decodedAssoc );
        
        if ( !$obj ) return false;

        $pedidom = new PM();

        $codigo = $obj->getCodigo();
        $cantidad = $obj->getCantidad();
        $cliente = $obj->getCliente();
        $producto = $obj->getProducto();
        $mesa = $obj->getMesa();
        $estado = $obj->getEstado();

        $objBD->setCodigo( $codigo );
        $objBD->setCantidad( $cantidad );
        $objBD->setCliente( $cliente );
        $objBD->setProducto( $producto );
        $objBD->setMesa( $mesa );
        $objBD->setEstado( $estado );

        return $pedidom->updateObject( $objBD );
    }

    public static function insert(Request $request, Response $response, array $args): Response
    {
        return parent::insert( $request, $response, $args );
    }

}