<?php

require_once __DIR__ . '/../interfaces/SerializeWithJSON.php';

/**
 * Representa un Tipo de Usuario.
 */
class TipoUsuario implements SerializeWithJSON
{

    private int $id;
    private string $tipo;

    public function __construct(
        int $id = -1, 
        string $tipo = '')
    {
        $this->id = $id;
        $this->tipo = $tipo;
    }

    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of tipo
     */
    public function getTipo() : string
    {
        return $this->tipo;
    }

    /**
     * Set the value of tipo
     *
     * @return  self
     */
    public function setTipo(string $tipo) : self
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        $ret = array();
        $ret["id"] = $this->id;
        $ret["tipo"] = $this->tipo;
        return $ret;
    }

    /**
     * Convierte de json a TipoUsuario.
     */
    public static function decode ( string $serialized ) : mixed {
        
        try {
            $assoc = json_decode($serialized, true, 512, JSON_THROW_ON_ERROR);
            return self::asssocToObj($assoc);
        }
        catch ( JsonException ) {
            return NULL;
        }
        
    }

    private static function asssocToObj( array $assoc ) : ?self {
        $keysHasToHave = array_keys( (new TipoUsuario())->jsonSerialize() );
        $keysHasHave = array_keys( $assoc );
        
        if ( count( array_diff( $keysHasToHave, $keysHasHave ) ) > 0 ) return NULL;

        $id = intval($assoc['id']);
        $ret = new TipoUsuario( $id,
                                $assoc['tipo'] );
        return $ret;
    }
    
}
