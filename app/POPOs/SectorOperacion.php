<?php

namespace POPOs;

/**
 * @Entity
 * @Table(name="SectorOperacion")
 */
class SectorOperacion implements \JsonSerializable {

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @Column
     */
    private string $sector;

    public function __construct(
                                int $id = -1,
                                string $sector = ""
    )
    {
        $this->id = $id;
        $this->sector = $sector;
    }

    /**
     * Get the value of id
     */ 
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of sector
     */ 
    public function getSector() : string
    {
        return $this->sector;
    }

    /**
     * Set the value of sector
     *
     * @return  self
     */ 
    public function setSector(string $sector)
    {
        $this->sector = $sector;

        return $this;
    }

    public function jsonSerialize()
    {
        $ret['id'] = $this->id;
        $ret['sector'] = $this->sector;
        return $ret;
    }
}