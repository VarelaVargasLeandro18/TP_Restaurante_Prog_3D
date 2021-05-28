<?php

use Firebase\JWT\JWT;

class Auth {

    private static array $encriptacion = ['HS256'];
    private static string $clave = "¿Yb irf... yb irf? Shv lb, uvwb zvb, dhvra gr qvb ybf cevzrebf nmbgrf, l ab gh cnqer, ndhv cerfragr, dhr unoevn cbqvqb unpreyb zrwbe dhr lb, chrf rf znf shregr.";
    
    private function __construct(){}

    public static function ObtenerToken( mixed $datos ) : string {
        $inicioHora = time();

        $token = array (
            'iat' => $inicioHora,
            'exp' => ( $inicioHora + (7*24*60*60) ),
            'data' => $datos,
            'aud' => self::Aud()
        );

        return JWT::encode(
            $token, 
            self::$clave, 
            self::$encriptacion[0]
        );
    }

    public static function Verificar( string $token ) : bool {
        $payload = NULL;

        if ( empty($token) ) return false;

        try {
            $payload = self::ObtenerPayload( $token );
        }
        catch (Exception $ex) {
            throw $ex;
        }

        return $payload->aud === self::Aud();
    }

    public static function ObtenerPayload( string $token ) : mixed {
        return JWT::decode( 
            $token, 
            self::$clave, 
            self::$encriptacion 
        );
    }

    public static function ObtenerDatos ( string $token ) : mixed {
        return self::ObtenerPayload($token) -> data;
    }

    private static function Aud () : string {
        $aud = '';

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $aud = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $aud = $_SERVER['REMOTE_ADDR'];
        }

        $aud .= @$_SERVER['HTTP_USER_AGENT'];
        $aud .= gethostname();

        return sha1($aud);
    }

}