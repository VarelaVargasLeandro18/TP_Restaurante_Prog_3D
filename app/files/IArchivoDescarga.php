<?php

namespace Files;

use Slim\Psr7\Stream;

interface IArchivoDescarga {

    public function crearArchivo(array $arrayDatos) : Stream;

}