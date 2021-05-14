<?php

class Empleado implements JsonSerializable
{

    private int $id;
    private string $nombre;
    private string $apellido;
    private Sector $sector;
    private TipoEmpleado $tipo;

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
     * Get the value of nombre
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */
    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get the value of apellido
     */
    public function getApellido(): string
    {
        return $this->apellido;
    }

    /**
     * Set the value of apellido
     *
     * @return  self
     */
    public function setApellido(string $apellido): self
    {
        $this->apellido = $apellido;

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
    public function setSector(Sector $sector)
    {
        $this->sector = $sector;

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

    public function jsonSerialize(): mixed
    {
        $ret = array();
        $ret["id"] = $this->id;
        $ret["nombre"] = $this->nombre;
        $ret["apellido"] = $this->apellido;
        $ret["sector"] = $this->sector->jsonSerialize();
        $ret["tipo"] = $this->tipo->jsonSerialize();

        return $ret;
    }
}
