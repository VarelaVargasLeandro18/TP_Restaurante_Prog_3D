<?php

namespace Middleware;

require_once __DIR__ . '/../models/Auth.php';

use Models\Auth;

class MWUsrDecode {

    protected static final function decodificarUsuario ( string $jwt ) : mixed {
        $jsonUsr = Auth::ObtenerDatos( $jwt );
        
        if ( $jsonUsr === NULL ) return NULL;

        $parsedUsr = json_decode( $jsonUsr, true );
        return $parsedUsr;
    }
}