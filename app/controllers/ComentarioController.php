<?php

namespace Controllers;

require_once __DIR__ . '/CRUDAbstractController.php';

# POPOs
require_once __DIR__ . '/../POPOs/Comentario.php';
use POPOs\Comentario as C;

# Models
require_once __DIR__ . '/../models/ComentarioModel.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/TipoComentarioModel.php';
require_once __DIR__ . '/../models/PedidoModel.php';
use Models\ComentarioModel as CM;
use Models\UsuarioModel as UM;
use Models\TipoComentarioModel as TCM;
use Models\PedidoModel as PM;

class ComentarioController extends CRUDAbstractController {

    protected static string $modelName = CM::class;
    protected static string $nombreClase = C::class;
    protected static int $PK_type = 1;

    protected static ?array $jsonConfig = array( 
        'IdTipoComentario' => '',
        'IdCliente' => '',
        'puntuacion' => '',
        'IdPedido' => '',
        'comentario' => ''
    );

    protected static function createObject(array $array): mixed
    {
        $tcm = new TCM();
        $um = new UM();
        $pm = new PM();

        $tc = $tcm->readById( $array['IdTipoComentario'] );
        $u = $um->readById( $array ['IdCliente'] );
        $p = $pm->readById( $array ['IdPedido'] );
        
        return new C(
            0,
            $tc,
            $u,
            $array['puntuacion'],
            $array['comentario'],
            $p
        );
    }

    protected static function updateObject(array $array, mixed $objBD): mixed
    {
        $obj = self::createObject($array);

        return  $objBD->setTipo( $obj->getTipo() )
                ->setCliente( $obj->getCliente() )
                ->setPuntuacion( $obj->getPuntuacion() )
                ->setComentario( $obj->getComentario() )
                ->setPedido( $obj->getPedido() );
    }

}