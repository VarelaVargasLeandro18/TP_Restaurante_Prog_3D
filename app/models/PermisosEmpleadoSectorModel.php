<?php

namespace Models;

require_once __DIR__ . '/../POPOs/PermisoEmpleadoSector.php';
require_once __DIR__ . '/CRUDAbstractModel.php';

use Models\CRUDAbstractModel;
use POPOs\PermisoEmpleadoSector;


class PermisosEmpleadoSectorModel extends CRUDAbstractModel {

    public function __construct()
    {
        parent::__construct( PermisoEmpleadoSector::class );
    }

    private function __clone()
    {}

}