<?php

/**
 * Representa un Producto que brinda un Sector determinado del local.
 */
class Producto implements JsonSerializable
{

    private int $id;
    private string $nombre;
    private string $tipo;
    private Sector $sector;

    public function __construct(int $id, string $nombre, string $tipo, Sector $sector)
    {
        $this->id = $id;
        $this->nombre = $nombre;
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
     * Get the value of tipo
     */
    public function getTipo(): string
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

    public function jsonSerialize(): mixed
    {
        $ret = array();
        $ret["id"] = $this->id;
        $ret["nombre"] = $this->nombre;
        $ret["tipo"] = $this->tipo;
        $ret["sector"] = $this->sector->jsonSerialize();
        return $ret;
    }
}
