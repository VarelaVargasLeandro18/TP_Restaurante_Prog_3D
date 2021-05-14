<?php

/**
 * Representa un Tipo de Empleado.
 */
class TipoEmpleado implements JsonSerializable
{

    private int $id;
    private string $tipo;

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
    public function setTipo($tipo)
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
}
