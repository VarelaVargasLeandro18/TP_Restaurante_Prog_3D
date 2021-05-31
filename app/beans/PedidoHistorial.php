<?php

require_once __DIR__ . '/../interfaces/SerializeWithJSON.php';

class PedidoHistorial implements SerializeWithJSON {

    private int $id;
    private string $codigo;
    private ?Producto $producto;
    private ?Usuario $responsable;
    private string $operacion;
    private string $fechaCambio;

    public function __construct(
                                int $id = -1,
                                string $codigo = '',
                                ?Producto $producto = NULL,
                                ?Usuario $responsable = NULL,
                                string $operacion = '',
                                string $fechaCambio = ''
    )
    {
        $this->id = $id;
        $this->codigo = $codigo;
        $this->producto = $producto;
        $this->responsable = $responsable;
        $this->operacion = $operacion;
        $this->fechaCambio = $fechaCambio;
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
     * Get the value of codigo
     */ 
    public function getCodigo() : string
    {
        return $this->codigo;
    }

    /**
     * Set the value of codigo
     *
     * @return  self
     */ 
    public function setCodigo(string $codigo) : self
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get the value of producto
     */ 
    public function getProducto() : ?Producto
    {
        return $this->producto;
    }

    /**
     * Set the value of producto
     *
     * @return  self
     */ 
    public function setProducto(?Producto $producto) : self
    {
        $this->producto = $producto;

        return $this;
    }

    /**
     * Get the value of responsable
     */ 
    public function getResponsable() : ?Usuario
    {
        return $this->responsable;
    }

    /**
     * Set the value of responsable
     *
     * @return  self
     */ 
    public function setResponsable(?Usuario $responsable) : self
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get the value of operacion
     */ 
    public function getOperacion() : string
    {
        return $this->operacion;
    }

    /**
     * Set the value of operacion
     *
     * @return  self
     */ 
    public function setOperacion(string $operacion) : self
    {
        $this->operacion = $operacion;

        return $this;
    }

    /**
     * Get the value of fechaCambio
     */ 
    public function getFechaCambio() : string
    {
        return $this->fechaCambio;
    }

    /**
     * Set the value of fechaCambio
     *
     * @return  self
     */ 
    public function setFechaCambio(string $fechaCambio) : self
    {
        $this->fechaCambio = $fechaCambio;

        return $this;
    }

    public function jsonSerialize() : mixed
    {
        $ret = array();
        $ret["id"] = $this->id;
        $ret["codigo"] = $this->codigo;
        $ret["productoId"] = ( $this->producto === NULL ) ? $this->producto->getId() : -1;
        $ret["responsableId"] = ( $this->responsable === NULL ) ? $this->responsable->getId() : -1;
        $ret["operacion"] = $this->operacion;
        $ret["fechaCambio"] = $this->fechaCambio;
        return $ret;
    }

    /**
     * Convierte de json a Producto.
     */
    public static function decode ( string $serialized ) : mixed {
        
        try {
            $assoc = json_decode($serialized, true, 512, JSON_THROW_ON_ERROR);
            return self::asssocToObj($assoc);
        }
        catch ( JsonException ) {
            return NULL;
        }
        
    }

    private static function asssocToObj( array $assoc ) : ?self {
        $keysHasToHave = array_keys( (new PedidoHistorial())->jsonSerialize() );
        $keysHasHave = array_keys( $assoc );
        
        if ( count( array_diff( $keysHasToHave, $keysHasHave ) ) > 0 ) return NULL;

        $id = intval($assoc["id"]);
        $producto = intval($assoc["productoId"]);
        $responsable = intval($assoc["responsableId"]);
        $ret = new PedidoHistorial( $id,
                                    $assoc["codigo"],
                                    new Producto( $producto ),
                                    new Usuario( $responsable ),
                                    $assoc["operacion"],
                                    $assoc["fechaCambio"]
        );

        return $ret;
    }

}