<?php

/**
 * Representa un Sector del local
 */
class Sector implements JsonSerializable
{

    private int $id;
    private string $nombre;

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

    public function jsonSerialize()
    {
        $ret = array();
        $ret["id"] = $this->id;
        $ret["nombre"] = $this->nombre;
        return $ret;
    }

}
