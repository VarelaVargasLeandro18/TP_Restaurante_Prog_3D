<?php

namespace Models;

require_once __DIR__ . '/../POPOs/PermisoEmpleadoSector.php';
require_once __DIR__ . '/CRUDAbstractImplementation.php';

use Models\CRUDAbstractImplementation;
use POPOs\PermisoEmpleadoSector;


class PermisosEmpleadoSectorModel extends CRUDAbstractImplementation {

    public function __construct()
    {
        parent::__construct( PermisoEmpleadoSector::class );
    }

    private function __clone()
    {}

}