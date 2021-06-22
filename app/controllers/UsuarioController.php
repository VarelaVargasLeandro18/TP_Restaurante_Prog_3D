<?php

namespace Controllers;

require_once __DIR__ . '/CRUDAbstractController.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/TipoUsuarioModel.php';
require_once __DIR__ . '/../POPOs/Usuario.php';
require_once __DIR__ . '/../files/CSVFileLoad.php';

use Psr\Http\Message\ServerRequestInterface as IRequest;
use Psr\Http\Message\ResponseInterface as IResponse;
use Fig\Http\Message\StatusCodeInterface as SCI;
use Files\CSVFileLoad;
use Models\UsuarioModel as UM;
use Models\TipoUsuarioModel as TUM;
use POPOs\Usuario as U;

/*
    "nombre": "",
    "apellido": "",
    "usuario": "",
    "contrasenia": ""
*/

class UsuarioController extends CRUDAbstractController {

    protected static string $modelName = UM::class;
    protected static string $nombreClase = 'Usuario';
    protected static int $PK_type = 1;
    protected static ?array $jsonConfig = array (
        "nombre" => '',
        "apellido" => '', 
        "usuario" => '',
        "contrasenia" => ''
    );

    protected static function createObject(array $array): mixed
    {
        $tipom = new TUM();
        $tipo = $tipom->readById( 6 );

        return (new U(
            0,
            $array['nombre'],
            $array['apellido'],
            $tipo,
            $array['usuario'],
        ))  
            ->setContrasenia( $array['contrasenia'] )
            ->setFechaIngreso( new \DateTime() );
    }

    protected static function updateObject(array $array, mixed $objBD): mixed
    {
        $obj = self::createObject($array);

        $objBD->setNombre( $obj->getNombre() );
        $objBD->setApellido ( $obj->getApellido() );
        $objBD->setTipo ( $obj->getTipo() );
        $objBD->setUsuario ( $obj->getUsuario() );
        $objBD->setContrasenia( $array['contrasenia'] );

        return $objBD;
    }

    private static function cambiarEstadoEmpleado ( IRequest $request, IResponse $response, array $args, bool $estado ) : IResponse {
        $id = intval($args['id']);
        $um = new UM();
        
        $usr = $um->readById($id);

        if ( $usr === NULL ) return $response->withStatus( SCI::STATUS_NOT_FOUND, 'No se encontró el ' . self::$nombreClase . ' especificado.' );

        $usr->setSuspendido($estado);

        if ( !$um->updateObject($usr) ) return $response->withStatus( SCI::STATUS_INTERNAL_SERVER_ERROR, 'Error al realizar el update del ' . self::$nombreClase );

        return $response->withStatus( SCI::STATUS_NO_CONTENT, 'Se realizó correctamente el update de ' . $usr->getNombre() );
    }

    public static function suspenderEmpleado ( IRequest $request, IResponse $response, array $args ) : IResponse {
        return self::cambiarEstadoEmpleado( $request, $response, $args, true );
    }

    public static function continuarEmpleado ( IRequest $request, IResponse $response, array $args ) : IResponse {
        return self::cambiarEstadoEmpleado( $request, $response, $args, false );
    }

    public static function crearUsuariosDeArchivo ( IRequest $request, IResponse $response, array $args ) : IResponse {
        $um = new UM();
        $tum = new TUM();
        $csvLoad = new CSVFileLoad( "archivoCSV" );
        $noInsertados = array();
        $datos = $csvLoad->obtenerDatosDeArchivo( array(
            'nombre',
            'apellido',
            'idTipo',
            'usuario',
            'contrasenia'
        ) );
        
        foreach ( $datos as $usr ) {
            $tipo = $tum->readById( intval($usr['usuario']) );
            $usuario = (new U(
                0,
                $usr['nombre'],
                $usr['apellido'],
                $tipo,
                $usr['usuario']
            ))  ->setContrasenia($usr['contrasenia'])
                ->setFechaIngreso( new \DateTime() );

            if ( !$um->insertObject($usuario) ) array_push($noInsertados, $usuario);
        }

        if ( !empty($noInsertados) ) {

            if ( count($noInsertados) === count($datos) ) return $response->withStatus(SCI::STATUS_INTERNAL_SERVER_ERROR, 'No se insertó ningún usuario');

            $response->getBody()->write( json_encode( array( 'noInsertados' => $noInsertados ), JSON_INVALID_UTF8_SUBSTITUTE ) );
            return $response->withStatus( SCI::STATUS_ACCEPTED, 'Hay usuarios que no se pudieron insertar.' );
        }

        return $response->withStatus(SCI::STATUS_NO_CONTENT, 'Se ingresaron correctamente los usuarios.');    
    }

    public static function cambiarTipoEmpleado ( IRequest $request, IResponse $response, array $args ) : IResponse {
        $json = $request->getBody()->__toString();
        $decodedBody = json_decode( $json, true );
        
        if ( !key_exists( 'id', $decodedBody ) || !key_exists( 'tipo', $decodedBody ) ) return $response->withStatus( SCI::STATUS_BAD_REQUEST, 'El json enviado no es correcto.' );

        $id = intval($decodedBody['id']);
        $tipo = intval($decodedBody['tipo']);

        $um = new UM();
        $u = $um->readById( $id );
        $tum = new TUM();
        $tu = $tum->readById( $tipo );

        if ( $u === NULL ) return $response->withStatus( SCI::STATUS_NOT_FOUND, 'No se ha encontrado el usuario con el id especificado.' );
        if ( $tu === NULL ) return $response->withStatus( SCI::STATUS_NOT_FOUND, 'No se encontró el tipo de usuario especificado.' );

        $u->setTipo( $tu );
        
        if ( !$um->updateObject( $u ) ) return $response->withStatus( SCI::STATUS_INTERNAL_SERVER_ERROR, 'No se pudo cambiar el tipo del usuario.' );
        
        return $response->withStatus( SCI::STATUS_NO_CONTENT, 'Se ha cambiado el tipo de empleado correctamente.' );
    }

}