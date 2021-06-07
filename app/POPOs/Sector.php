<?php

namespace POPOs;

/**
 * Representa un Sector del local
 * @Entity
 * @Table(name="Sector")
 */
class Sector implements \JsonSerializable
{

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @Column(length=45)
     */
    private string $nombre;

    public function __construct(
                                int $id = -1, 
                                string $nombre = '')
    {
        $this->id = $id;
        $this->nombre = $nombre;
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

    public function jsonSerialize()
    {
        $ret = array();
        if( isset($this->id) )
            $ret["id"] = $this->id;
        $ret["nombre"] = $this->nombre;
        return $ret;
    }
}
