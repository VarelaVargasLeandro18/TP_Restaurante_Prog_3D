<?php

namespace POPOs;

require_once __DIR__ . '/TipoUsuario.php';
require_once __DIR__ . '/Sector.php';

/**
 * Representa el permiso que tiene un Empleado para cambiar estado de un Pedido según el Sector en el que esté.
 * @Entity
 * @Table(name="PermisosEmpleadoSector") 
 */
class PermisoEmpleadoSector implements \JsonSerializable
{

    /**
     * @Id
     * @Column(type="integer")
     */
    private int $id;

    /**
     * @ManyToOne(targetEntity="TipoUsuario")
     * @JoinColumn(name="tipoEmpleadoId", referencedColumnName="id", nullable=false)
     */
    private ?TipoUsuario $tipo;

    /**
     * @ManyToOne(targetEntity="Sector")
     * @JoinColumn(name="sectorId", referencedColumnName="id")
     */
    private ?Sector $sector;

    /**
     * @Column(length=45)
     */
    private string $permisos;

    public function __construct(    int $id = -1, 
                                    ?TipoUsuario $tipo = NULL, 
                                    ?Sector $sector = NULL,
                                    string $permisos = '')
    {
        $this->id = $id;
        $this->tipo = $tipo;
        $this->sector = $sector;
        $this->permisos = $permisos;
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
    public function getTipo(): ?TipoUsuario
    {
        return $this->tipo;
    }

    /**
     * Set the value of tipo
     *
     * @return  self
     */
    public function setTipo(?TipoUsuario $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get the value of sector
     */
    public function getSector(): ?Sector
    {
        return $this->sector;
    }

    /**
     * Set the value of sector
     *
     * @return  self
     */
    public function setSector(?Sector $sector): self
    {
        $this->sector = $sector;

        return $this;
    }

    /**
     * Get the value of permisos
     */ 
    public function getPermisos() : string
    {
        return $this->permisos;
    }

    /**
     * Set the value of permisos
     *
     * @return  self
     */ 
    public function setPermisos(string $permisos)
    {
        $this->permisos = $permisos;

        return $this;
    }

    public function jsonSerialize() : mixed
    {
        $ret = array();
        $ret["id"] = $this->id;
        $ret["tipoId"] = ( $this->tipo !== NULL ) ? $this->tipo->getId() : -1; 
        $ret["sector"] = $this->sector;
        $ret["permisos"] = $this->permisos;
        return $ret;
    }
}
