<?php

require_once __DIR__ . '/../controllers/ConsultasController.php';
use Controllers\ConsultasController as CC;
use Slim\Routing\RouteCollectorProxy;


$app->group ( '/consulta', function ( RouteCollectorProxy $group ) {
    
    $group->get( '/usuario', CC::class . '::fechaHorariosUsuarios' );
    $group->get('/cantOpCadaUnoSector/{idSector}', CC::class . '::cantOperacionesCadaUnoPorSector');
    $group->get('/cantOpSector/{idSector}', CC::class . '::cantOpXSector');

    $group->get( '/masVendido', CC::class . '::masVendido' );
    $group->get( '/menosVendido', CC::class . '::menosVendido');
    $group->get( '/entregadosTarde', CC::class . '::pedidosEntregadosTarde' );
    $group->get( '/cancelados', CC::class . '::cancelados' );

    

} );