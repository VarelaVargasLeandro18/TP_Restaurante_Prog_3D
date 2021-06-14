<?php

require_once __DIR__ . '/../controllers/ConsultasController.php';
use Controllers\ConsultasController as CC;
use Slim\Routing\RouteCollectorProxy;


$app->group ( '/consulta', function ( RouteCollectorProxy $group ) {
    
    $group->get( '/usuario', CC::class . '::fechaHorariosUsuarios' );
    $group->get('/cantOpSector/{idSector}', CC::class . '::cantOperacionesTodosPorSector');
    $group->get('/cantOp', CC::class . '::cantOpCadaUno');

    $group->get( '/masVendido/{codigo}', CC::class . '::masVendido' );
    $group->get( '/menosVendido/{codigo}', CC::class . '::menosVendido');

} );