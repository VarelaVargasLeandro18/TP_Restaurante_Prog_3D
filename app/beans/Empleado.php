<?php

class Empleado implements JsonSerializable
{

    private int $id;
    private string $nombre;
    private string $apellido;
    private Sector $sector;
    private TipoEmpleado $tipo;
    private string $usuario;
    private string $contraseniaHash;
    private DateTime $fechaIngreso;
    private int $cantOperaciones;

    public function __construct(int $id, 
                                string $nombre, 
                                string $apellido,
                                Sector $sector,
                                TipoEmpleado $tipo,
                                string $usuario,
                                string $contraseniaHash)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->sector = $sector;
        $this->tipo = $tipo;
        $this->usuario = $usuario;
        $this->contraseniaHash = $contraseniaHash;
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

    /**
     * Get the value of usuario
     */ 
    public function getUsuario() : string
    {
        return $this->usuario;
    }

    /**
     * Set the value of usuario
     *
     * @return  self
     */ 
    public function setUsuario(string $usuario) : self
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get the value of contraseniaHash
     */ 
    public function getContraseniaHash() : string
    {
        return $this->contraseniaHash;
    }

    /**
     * Set the value of contraseniaHash
     *
     * @return  self
     */ 
    public function setContraseniaHash(string $contraseniaHash)
    {
        $this->contraseniaHash = $contraseniaHash;

        return $this;
    }

    /**
     * Get the value of fechaIngreso
     */ 
    public function getFechaIngreso() : DateTime
    {
        return $this->fechaIngreso;
    }

    /**
     * Set the value of fechaIngreso
     *
     * @return  self
     */ 
    public function setFechaIngreso(DateTime $fechaIngreso)
    {
        $this->fechaIngreso = $fechaIngreso;

        return $this;
    }


    /**
     * Get the value of cantOperaciones
     */ 
    public function getCantOperaciones()
    {
        return $this->cantOperaciones;
    }

    /**
     * Set the value of cantOperaciones
     *
     * @return  self
     */ 
    public function setCantOperaciones($cantOperaciones)
    {
        $this->cantOperaciones = $cantOperaciones;

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
        $ret["usuario"] = $this->usuario;

        return $ret;
    }
}
