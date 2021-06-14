<?php

namespace Files;

interface IArchivoCarga {

    public static function verificarYobtenerPathArchivo ( string $postFormName ) : ?string;
    public function obtenerDatosDeArchivo ( array $nombreAtributosEnOrden ) : array;

}