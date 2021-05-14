<?php

/**
 * Esta clase representa los permisos que tiene un Empleado para cambiar el estado de una Mesa.
 */
class PermisosEmpleadoMesa implements JsonSerializable
{

    private int $id;
    private TipoEmpleado $tipoEmpleado;
    private EstadoMesa $estadoMesa;

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
     * Get the value of tipoEmpleado
     */ 
    public function getTipoEmpleado() : TipoEmpleado
    {
        return $this->tipoEmpleado;
    }

    /**
     * Set the value of tipoEmpleado
     *
     * @return  self
     */ 
    public function setTipoEmpleado(TipoEmpleado $tipoEmpleado) : self
    {
        $this->tipoEmpleado = $tipoEmpleado;

        return $this;
    }

    /**
     * Get the value of estadoMesa
     */
    public function getEstadoMesa(): EstadoMesa
    {
        return $this->estadoMesa;
    }

    /**
     * Set the value of estadoMesa
     *
     * @return  self
     */
    public function setEstadoMesa(EstadoMesa $estadoMesa): self
    {
        $this->estadoMesa = $estadoMesa;

        return $this;
    }

    public function jsonSerialize() : mixed
    {
        $ret = array();
        $ret["id"] = $this->id;
        $ret["tipoEmpleado"] = $this->tipoEmpleado->jsonSerialize();
        $ret["estadoMesa"] = $this->estadoMesa->jsonSerialize();
        return $ret;
    }

}
