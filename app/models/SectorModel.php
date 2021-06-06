<?php

namespace Models;

require_once __DIR__ . '/../POPOs/Sector.php';
require_once __DIR__ . '/CRUDAbstractImplementation.php';

use POPOs\Sector;
use Models\CRUDAbstractImplementation;

class SectorModel extends CRUDAbstractImplementation {

    public function __construct()
    {
        parent::__construct( Sector::class );
    }

    private function __clone()
    {}

}