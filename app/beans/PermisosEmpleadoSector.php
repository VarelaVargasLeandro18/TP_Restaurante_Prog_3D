<?php

/**
 * Representa el permiso que tiene un Empleado para cambiar estado de un Pedido según el Sector en el que esté. 
 */
class PermisosEmpleadoSector implements JsonSerializable
{

    private int $id;
    private TipoEmpleado $tipo;
    private Sector $sector;

    public function __construct(int $id, TipoEmpleado $tipo, Sector $sector)
    {
        $this->id = $id;
        $this->tipo = $tipo;
        $this->sector = $sector;
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
    public function getTipo(): TipoEmpleado
    {
        return $this->tipo;
    }

    /**
     * Set the value of tipo
     *
     * @return  self
     */
    public function setTipo(TipoEmpleado $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get the value of sector
     */
    public function getSector(): Sector
    {
        return $this->sector;
    }

    /**
     * Set the value of sector
     *
     * @return  self
     */
    public function setSector(Sector $sector): self
    {
        $this->sector = $sector;

        return $this;
    }

    public function jsonSerialize() : mixed
    {
        $ret = array();
        $ret["id"] = $this->id;
        $ret["tipo"] = $this->tipo->jsonSerialize();
        $ret["sector"] = $this->sector->jsonSerialize();
        return $ret;
    }

}
