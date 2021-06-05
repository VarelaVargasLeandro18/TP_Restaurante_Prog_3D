<?php

require_once __DIR__ . '/Pojos/TipoComentario.php';
require_once __DIR__ . '/db/DoctrineEntityManagerFactory.php';
require_once __DIR__ . '/Pojos/EstadoMesa.php';
require_once __DIR__ . '/Pojos/PedidoEstado.php';

use Pojos\TipoComentario as TC;
use Pojos\EstadoMesa as EM;
use Pojos\PedidoEstado as PE;
use db\DoctrineEntityManagerFactory as DEMF;
use GuzzleHttp\Psr7\Response;


$app->get ( '/pruebaTC', function () {
    $TCRep = DEMF::getEntityManager()->getRepository(TC::class);
    
    return new Response( 200, [], $TCRep->find(1) );
} );

$app->get ( '/pruebaEM', function() {
    $TCRep = DEMF::getEntityManager()->getRepository(EM::class);

    return new Response( 200, [], $TCRep->find(1) );
} );

$app->get ( '/pruebaPE', function() {
    $TCRep = DEMF::getEntityManager()->getRepository(PE::class);

    return new Response(200, [], $TCRep->find(1));
} );