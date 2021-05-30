<?php

require_once __DIR__ . '/../interfaces/SerializeWithJSON.php';

class Empleado implements SerializeWithJSON
{

    private int $id;
    private string $nombre;
    private string $apellido;
    private ?TipoEmpleado $tipo;
    private string $usuario;
    private string $contraseniaHash;
    private string $fechaIngreso;
    private int $cantOperaciones;
    private bool $suspendido;

    public function __construct(int $id = -1, 
                                string $nombre = '', 
                                string $apellido = '',
                                ?TipoEmpleado $tipo = NULL,
                                string $usuario = '',
                                string $contraseniaHash = '',
                                int $cantOperaciones = 0,
                                bool $suspendido = false)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->tipo = $tipo;
        $this->usuario = $usuario;
        $this->contraseniaHash = $contraseniaHash;
        $this->cantOperaciones = $cantOperaciones;
        $this->suspendido = $suspendido;
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
     * Get the value of tipo
     */
    public function getTipo(): ?TipoEmpleado
    {
        return $this->tipo;
    }

    /**
     * Set the value of tipo
     *
     * @return  self
     */
    public function setTipo(?TipoEmpleado $tipo): self
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
    public function getFechaIngreso() : string
    {
        return $this->fechaIngreso;
    }

    /**
     * Set the value of fechaIngreso
     *
     * @return  self
     */ 
    public function setFechaIngreso(string $fechaIngreso)
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

    /**
     * Get the value of suspendido
     */ 
    public function getSuspendido()
    {
        return $this->suspendido;
    }

    /**
     * Set the value of suspendido
     *
     * @return  self
     */ 
    public function setSuspendido($suspendido)
    {
        $this->suspendido = $suspendido;

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        $ret = array();
        $ret["id"] = $this->id;
        $ret["nombre"] = $this->nombre;
        $ret["apellido"] = $this->apellido;

        $ret["sector"] = null;
        if ( $this->sector ) $ret["sector"] = $this->sector->jsonSerialize();
        
        $ret["tipo"] = null;
        if ( $this->tipo ) $ret["tipo"] = $this->tipo->jsonSerialize();

        $ret["usuario"] = $this->usuario;
        $ret["fechaIngreso"] = $this->fechaIngreso;
        $ret["cantOperaciones"] = $this->cantOperaciones;
        $ret["suspendido"] = $this->suspendido;

        return $ret;
    }

    /**
     * Convierte de un json a Empleado.
     * @param string $serialized JSON de Empleado.
     * @return mixed empleado.
     */
    public static function decode( string $serialized ) : mixed {
        
        try {
            $assoc = json_decode($serialized, true);

            $id = intval($assoc['id']);
            $nombre = $assoc['nombre'];
            $apellido = $assoc['apellido'];

            $sector = null;
            if ( $assoc['sector'] !== NULL ) $sector = new Sector( intval($assoc['sector']['id']), $assoc['sector']['nombre'] );

            $tipo = null;
            if ( $assoc['tipo'] !== NULL ) $tipo = new TipoEmpleado( intval($assoc['tipo']['id']), $assoc['tipo']['tipo'] );
            
            $usuario = $assoc['usuario'];
            $contrasenia = $assoc['contrasenia'];
            $fecha = $assoc['fechaIngreso'];
            $cantOp = intval($assoc['cantOperaciones']);
            $suspendido = $assoc['suspendido'] === "true";

            $ret = new Empleado( 
                $id,
                $nombre, $apellido,
                $sector,
                $tipo,
                $usuario,
                $contrasenia,
                $suspendido
            );
            $ret->setFechaIngreso($fecha);
            $ret->setCantOperaciones($cantOp);

            return $ret;
        }
        catch ( JsonException $ex ) {
            return NULL;
        }

    }
}
