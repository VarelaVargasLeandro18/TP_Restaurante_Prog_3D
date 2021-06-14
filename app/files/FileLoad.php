<?php

namespace Files;

class FileLoad {

    public static function verificarYobtenerPathArchivo ( string $postFormName ) : ?string {
        return ( key_exists( $postFormName, $_FILES ) ) ? $_FILES[$postFormName]['tmp_name'] : NULL;
    }

}