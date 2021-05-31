<?php

require_once __DIR__ . '/../interfaces/SerializeWithJSON.php';

/**
 * Representa el estado de un pedido.
 */
class PedidoEstado implements SerializeWithJSON
{

    private int $id;
    private string $estado;

    public function __construct(int $id = -1, 
                                string $estado = '')
    {
        $this->id = $id;
        $this->estado = $estado;
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
     * Get the value of estado
     */
    public function getEstado(): string
    {
        return $this->estado;
    }

    /**
     * Set the value of estado
     *
     * @return  self
     */
    public function setEstado(string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function jsonSerialize() : mixed
    {
        $ret = array();
        $ret["id"] = $this->id;
        $ret["estado"] = $this->estado;
        return $ret;
    }

    /**
     * Convierte de json a PedidoHistoral.
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
        $keysHasToHave = array_keys( (new PedidoEstado())->jsonSerialize() );
        $keysHasHave = array_keys( $assoc );
        
        if ( count( array_diff( $keysHasToHave, $keysHasHave ) ) > 0 ) return NULL;

        $id = intval($assoc["id"]);
        $ret = new PedidoEstado( $id,
                                 $assoc["estado"]
        );

        return $ret;
    }

}
