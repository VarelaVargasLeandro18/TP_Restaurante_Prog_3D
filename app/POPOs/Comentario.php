<?php
namespace POPOs;

require_once __DIR__ . '/../interfaces/SerializeWithJSON.php';

use interfaces\SerializeWithJSON as SWJ;

/**
 * Representa un comentario realizado por un cliente al restaurante.
 * @Entity
 * @Table(name="Comentario")
 */
class Comentario implements SWJ {

    /**
     * @Id
     * @Column(type="integer")
     */
    private int $id;

    /**
     * @ManyToOne(targetEntity="TipoComentario")
     * @JoinColumn(name="tipoComentarioId", referencedColumnName="id")
     */
    private ?TipoComentario $tipo;

    /**
     * @ManyToOne(targetEntity="Usuario")
     * @JoinColumn(name="clienteId", referencedColumnName="id")
     */
    private ?Usuario $cliente;
    
    /**
     * @Column(type="integer")
     */
    private int $puntuacion;

    /**
     * @Column(type="string")
     */
    private string $comentario;

    public function __construct(
                                int $id = -1,
                                ?TipoComentario $tipo = NULL,
                                ?Usuario $cliente = NULL,
                                int $puntuacion = 0,
                                string $comentario = ''
    )
    {
        $this->id = $id;
        $this->tipo = $tipo;
        $this->cliente = $cliente;
        $this->puntuacion = $puntuacion;
        $this->comentario = $comentario;
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
    public function setId(int $id) : self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of tipo
     */ 
    public function getTipo() : ?TipoComentario
    {
        return $this->tipo;
    }

    /**
     * Set the value of tipo
     *
     * @return  self
     */ 
    public function setTipo(?TipoComentario $tipo) : self
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get the value of cliente
     */ 
    public function getCliente() : ?Usuario
    {
        return $this->cliente;
    }

    /**
     * Set the value of cliente
     *
     * @return  self
     */ 
    public function setCliente(?Usuario $cliente) : self
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get the value of puntuacion
     */ 
    public function getPuntuacion() : int
    {
        return $this->puntuacion;
    }

    /**
     * Set the value of puntuacion
     *
     * @return  self
     */ 
    public function setPuntuacion(int $puntuacion) : self
    {
        $this->puntuacion = $puntuacion;

        return $this;
    }

    /**
     * Get the value of comentario
     */ 
    public function getComentario() : string
    {
        return $this->comentario;
    }

    /**
     * Set the value of comentario
     *
     * @return  self
     */ 
    public function setComentario(string $comentario) : self
    {
        $this->comentario = $comentario;

        return $this;
    }

    public function jsonSerialize() : mixed
    {
        $ret = array();
        $ret["id"] = $this->id;
        $ret["tipo"] = $this->tipo;
        $ret["cliente"] = $this->cliente !== NULL;
        $ret["puntuacion"] = $this->puntuacion;
        $ret["comentario"] = $this->comentario;
        return $ret;
    }

    /**
     * Convierte de json a Pedido.
     */
    public static function decode ( string $serialized ) : mixed {
        
        try {
            $assoc = json_decode($serialized, true, 512, JSON_THROW_ON_ERROR);
            return self::asssocToObj($assoc);
        }
        catch ( \Exception ) {
            return NULL;
        }
        
    }

    private static function asssocToObj( array $assoc ) : ?self {
        $keysHasToHave = array_keys( (new Comentario())->jsonSerialize() );
        $keysHasHave = array_keys( $assoc );
        
        if ( count( array_diff( $keysHasToHave, $keysHasHave ) ) > 0 ) return NULL;

        $id = intval($assoc["id"]);
        $tipo = intval($assoc["tipoId"]);
        $cliente = intval($assoc["clienteId"]);
        $puntuacion = intval($assoc["puntuacion"]);

        $ret = new Comentario(  
                                $id,
                                new TipoComentario($tipo),
                                new Usuario($cliente),
                                $puntuacion,
                                $assoc["comentario"] 
        );

        return $ret;
    }
}