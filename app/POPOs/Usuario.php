<?php
namespace POPOs;

require_once __DIR__ . '/../interfaces/SerializeWithJSON.php';

use Doctrine\ORM\Mapping;
use interfaces\SerializeWithJSON as SWJ;

/**
 * Representa un usuario.
 * @Entity
 * @Table(name="Usuario")
 */
class Usuario implements SWJ
{

    /**
     * @Id
     * @Column(type="integer")
     */
    private int $id;

    /**
     * @Column(length=45)
     */
    private string $nombre;

    /**
     * @Column(length=45)
     */
    private string $apellido;

    /**
     * @ManyToOne(targetEntity="TipoUsuario")
     * @JoinColumn(name="tipoUsuarioId", referencedColumnName="id")
     */
    private ?TipoUsuario $tipo;

    /**
     * @Column(length=45)
     */
    private string $usuario;

    /**
     * @Column(name="contraseniaHash", length=60)
     */
    private string $contrasenia;

    /**
     * @Column
     */
    private string $fechaIngreso;

    /**
     * @Column(name="cantidadOperaciones", type="integer")
     */
    private int $cantOperaciones;

    /**
     * @Column(type="boolean")
     */
    private bool $suspendido;

    public function __construct(int $id = -1, 
                                string $nombre = '', 
                                string $apellido = '',
                                ?TipoUsuario $tipo = NULL,
                                string $usuario = '',
                                string $contrasenia = '',
                                int $cantOperaciones = 0,
                                bool $suspendido = false)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->tipo = $tipo;
        $this->usuario = $usuario;
        $this->contrasenia = $contrasenia;
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
     * Get the value of contrasenia
     */ 
    public function getContrasenia() : string
    {
        return $this->contrasenia;
    }

    /**
     * Set the value of contrasenia
     *
     * @return  self
     */ 
    public function setContrasenia(string $contrasenia)
    {
        $this->contrasenia = $contrasenia;

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
    public function setFechaIngreso(string $fechaIngreso) : self
    {
        $this->fechaIngreso = $fechaIngreso;

        return $this;
    }

    /**
     * Get the value of cantOperaciones
     */ 
    public function getCantOperaciones() : int
    {
        return $this->cantOperaciones;
    }

    /**
     * Set the value of cantOperaciones
     *
     * @return  self
     */ 
    public function setCantOperaciones(int $cantOperaciones) : self
    {
        $this->cantOperaciones = $cantOperaciones;

        return $this;
    }

    /**
     * Get the value of suspendido
     */ 
    public function getSuspendido() : bool
    {
        return $this->suspendido;
    }

    /**
     * Set the value of suspendido
     *
     * @return  self
     */ 
    public function setSuspendido(bool $suspendido) : self
    {
        $this->suspendido = $suspendido;

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        $ret = array();
        $ret['id'] = $this->id;
        $ret['nombre'] = $this->nombre;
        $ret['apellido'] = $this->apellido;
        $ret['tipoId'] = $this->tipo;
        $ret['usuario'] = $this->usuario;
        $ret['contrasenia'] = $this->contrasenia;
        $ret['fechaIngreso'] = $this->fechaIngreso;
        $ret['cantOperaciones'] = $this->cantOperaciones;
        $ret['suspendido'] = $this->suspendido;

        return $ret;
    }

    /**
     * Convierte de un json a Usuario.
     * @param string $serialized JSON de Empleado.
     * @return mixed Usuario.
     */
    public static function decode( string $serialized ) : mixed {
        
        try {
            $assoc = json_decode($serialized, true);
            return self::assocToObj($assoc);
        }
        catch ( \Exception ) {
            return NULL;
        }

    }

    /**
     * Convierte un array asociativo de un json_decode a un objeto de tipo Usuario.
     */
    private static function assocToObj( array $assoc ) : ?self {
        $keysHasToHave = array_keys( (new Usuario())->jsonSerialize() );
        $keysHasHave = array_keys( $assoc );
        
        if ( count( array_diff( $keysHasToHave, $keysHasHave ) ) > 0 ) return NULL;

        $id = intval($assoc['id']);
        $tipoId = intval($assoc['tipoId']);
        $cantOp = intval($assoc['cantOperaciones']);
        $suspendido = boolval($assoc['suspendido']);

        $ret = new Usuario( $id,
                            $assoc['nombre'],
                            $assoc['apellido'],
                            new TipoUsuario( $tipoId ),
                            $assoc['usuario'],
                            $assoc['contrasenia'],
                            $cantOp,
                            $suspendido );

        return $ret;
    }

    public function __toString()
    {
        return json_encode( $this->jsonSerialize() );
    }
}
