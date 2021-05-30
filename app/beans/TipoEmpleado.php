<?php

require_once __DIR__ . '/../interfaces/SerializeWithJSON.php';

/**
 * Representa un Tipo de Empleado.
 */
class TipoEmpleado implements SerializeWithJSON
{

    private int $id;
    private string $tipo;

    public function __construct(
        int $id, 
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
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set the value of tipo
     *
     * @return  self
     */
    public function setTipo(string $tipo)
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

    public static function decode ( string $serialized ) : mixed {
        
        try {
            $assoc = json_decode($serialized, true, 512, JSON_THROW_ON_ERROR);

            $id = intval($assoc['id']);
            $tipo = $assoc['tipo'];
            $ret = new TipoEmpleado( $id, $tipo );

            return $ret;
        }
        catch ( JsonException $ex ) {
            return NULL;
        }
        
    }
    
}
