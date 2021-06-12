<?php

namespace Controllers;

require_once __DIR__ . '/../models/PedidoModel.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/MesaModel.php';
require_once __DIR__ . '/../db/DoctrineEntityManagerFactory.php';

require_once __DIR__ . '/CRUDAbstractController.php';

use Models\PedidoModel as PM;
use Models\UsuarioModel as UM;
use Models\MesaModel as MM;

use POPOs\Pedido as P;

use db\DoctrineEntityManagerFactory as DEMF;
use Fig\Http\Message\StatusCodeInterface as SCI;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/*  Id(código) autogenerado
    'cantidad': -1,
    'IdCliente': -1,
*/

class PedidoController extends CRUDAbstractController {

    protected static string $modelName = PM::class;
    protected static string $nombreClase = 'Pedido';
    protected static int $PK_type = 1;

    protected static ?array $jsonConfig = array( 
        'IdCliente' => '',
        'IdMesa' => ''
    );

    private static string $lastGeneratedCode;

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

        $queryBuilder->select('p.codigo')
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
        self::$lastGeneratedCode = self::generarCodigo();
        $usuariom = new UM();
        $mesam = new MM();

        $cliente = $usuariom->readById( $array['IdCliente'] );
        $mesa = $mesam->readById( $array['IdMesa'] );
        
        return new P(
            self::$lastGeneratedCode,
            $cliente,
            $mesa
        );
    }

    protected static function updateObject(array $array, mixed $objBD): mixed
    {
        $objT = self::createObject($array);
        $objBD->setCliente($objT->getCliente());
        $objBD->setMesa($objT->getMesa());
        return $objBD;
    }

    public static function insert(RequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $response = parent::insert( $request, $response, $args );

        if ( $response->getStatusCode() === SCI::STATUS_CREATED ) 
            $response->getBody()->write( json_encode( array( 'codigo' => self::$lastGeneratedCode ) ) );

        return $response;
    }

}