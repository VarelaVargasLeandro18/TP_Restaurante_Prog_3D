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
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use Psr\Http\Message\UploadedFileInterface;

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
    public static string $imgPath = '';

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

    public static function insert(Request $request, Response $response, array $args): Response
    {
        $response = parent::insert( $request, $response, $args );

        if ( $response->getStatusCode() === SCI::STATUS_CREATED ) 
            $response->getBody()->write( json_encode( array( 'codigo' => self::$lastGeneratedCode ) ) );

        return $response;
    }

    public static function agregarImagen(ServerRequest $request, Response $response, array $args) : Response {
        $pm = new PM();
        if ( $pm->readById($args['codigoPedido']) === NULL ) return $response->withStatus(SCI::STATUS_NOT_FOUND, 'No existe el pedido con el id especificado.');
        if ( !isset(self::$imgPath) || empty(self::$imgPath) ) return $response->withStatus( SCI::STATUS_INTERNAL_SERVER_ERROR, "No se pudo guardar la imagen." );
        if ( !key_exists('imagenPedido', $_FILES) ) return $response->withStatus(SCI::STATUS_BAD_REQUEST, 'No se subió correctamente el archivo.');
        if ( !is_uploaded_file( $_FILES['imagenPedido']['tmp_name'] ) ) return $response->withStatus(SCI::STATUS_BAD_REQUEST, 'No se subió correctamente el archivo.');
        
        $tmppath = $_FILES['imagenPedido']['tmp_name'];
        
        if ( getimagesize($tmppath) === false ) return $response->withStatus(SCI::STATUS_BAD_REQUEST, 'El archivo subido no es una imagen');
        
        $nuevonombre = $args['codigoPedido'] . '.' . pathinfo($_FILES['imagenPedido']['name'], PATHINFO_EXTENSION);
        $pathCompleto = $_SERVER['DOCUMENT_ROOT'] . '/' . self::$imgPath . $nuevonombre;
        
        if ( !move_uploaded_file( $tmppath, $pathCompleto ) ) return $response->withStatus(SCI::STATUS_INTERNAL_SERVER_ERROR, 'No se pudo guardar la imagen.');

        return $response->withStatus(SCI::STATUS_CREATED, 'La imagen se guardó exitosamente.');
    }

}