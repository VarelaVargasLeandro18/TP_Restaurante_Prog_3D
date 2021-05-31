<?php

require_once __DIR__ . '/../interfaces/SerializeWithJSON.php';

/**
 * Representa un pedido realizado por un cliente
 */
class Pedido implements SerializeWithJSON
{

    private int $id;
    private string $codigo;
    private int $cantidad;
    private ?Usuario $cliente;
    private ?Usuario $empleadoTomaPedido;
    private ?Producto $producto;
    private ?Mesa $mesa;
    private ?PedidoEstado $estado;
    private string $imgPath;
    private string $fechaHoraInicioPedido;
    private string $fechaHoraFinPedidoEstipulada;
    private string $fechaHoraFinPedido;

    public function __construct(
                                int $id = -1,
                                string $codigo = '',
                                int $cantidad = 0,
                                ?Usuario $cliente = NULL,
                                ?Usuario $empleadoTomaPedido = NULL,
                                ?Producto $producto = NULL,
                                ?Mesa $mesa = NULL,
                                ?PedidoEstado $estado = NULL,
                                string $imgPath = '',
                                string $fechaHoraInicioPedido = '',
                                string $fechaHoraFinPedidoEstipulada = '',
                                string $fechaHoraFinPedido = ''
                                )
    {
        $this->id = $id;
        $this->codigo = $codigo;
        $this->cantidad = $cantidad;
        $this->cliente = $cliente;
        $this->empleadoTomaPedido = $empleadoTomaPedido;
        $this->producto = $producto;
        $this->mesa = $mesa;
        $this->estado = $estado;
        $this->imgPath = $imgPath;
        $this->fechaHoraInicioPedido = $fechaHoraInicioPedido;
        $this->fechaHoraInicioPedidoEstipulada = $fechaHoraFinPedidoEstipulada;
        $this->fechaHoraFinPedido = $fechaHoraFinPedido;
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
     * Get the value of cantidad
     */ 
    public function getCantidad() : int
    {
        return $this->cantidad;
    }

    /**
     * Set the value of cantidad
     *
     * @return  self
     */ 
    public function setCantidad(int $cantidad) : self
    {
        $this->cantidad = $cantidad;

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
     * Get the value of empleadoTomaPedido
     */ 
    public function getEmpleadoTomaPedido() : ?Usuario
    {
        return $this->empleadoTomaPedido;
    }

    /**
     * Set the value of empleadoTomaPedido
     *
     * @return  self
     */ 
    public function setEmpleadoTomaPedido(?Usuario $empleadoTomaPedido) : self
    {
        $this->empleadoTomaPedido = $empleadoTomaPedido;

        return $this;
    }

    /**
     * Get the value of producto
     */ 
    public function getProducto()
    {
        return $this->producto;
    }

    /**
     * Set the value of producto
     *
     * @return  self
     */ 
    public function setProducto($producto)
    {
        $this->producto = $producto;

        return $this;
    }

    /**
     * Get the value of mesa
     */ 
    public function getMesa() : ?Mesa
    {
        return $this->mesa;
    }

    /**
     * Set the value of mesa
     *
     * @return  self
     */ 
    public function setMesa(?Mesa $mesa) : self
    {
        $this->mesa = $mesa;

        return $this;
    }

    /**
     * Get the value of estado
     */ 
    public function getEstado() : ?PedidoEstado
    {
        return $this->estado;
    }

    /**
     * Set the value of estado
     *
     * @return  self
     */ 
    public function setEstado(?PedidoEstado $estado) : self
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get the value of imgPath
     */ 
    public function getImgPath() : string
    {
        return $this->imgPath;
    }

    /**
     * Set the value of imgPath
     *
     * @return  self
     */ 
    public function setImgPath(string $imgPath) : self
    {
        $this->imgPath = $imgPath;

        return $this;
    }

    /**
     * Get the value of fechaHoraInicioPedido
     */ 
    public function getFechaHoraInicioPedido() : string
    {
        return $this->fechaHoraInicioPedido;
    }

    /**
     * Set the value of fechaHoraInicioPedido
     *
     * @return  self
     */ 
    public function setFechaHoraInicioPedido(string $fechaHoraInicioPedido) : self
    {
        $this->fechaHoraInicioPedido = $fechaHoraInicioPedido;

        return $this;
    }

    /**
     * Get the value of fechaHoraFinPedidoEstipulada
     */ 
    public function getFechaHoraFinPedidoEstipulada() : string
    {
        return $this->fechaHoraFinPedidoEstipulada;
    }

    /**
     * Set the value of fechaHoraFinPedidoEstipulada
     *
     * @return  self
     */ 
    public function setFechaHoraFinPedidoEstipulada(string $fechaHoraFinPedidoEstipulada) : self
    {
        $this->fechaHoraFinPedidoEstipulada = $fechaHoraFinPedidoEstipulada;

        return $this;
    }

    /**
     * Get the value of fechaHoraFinPedido
     */ 
    public function getFechaHoraFinPedido() : string
    {
        return $this->fechaHoraFinPedido;
    }

    /**
     * Set the value of fechaHoraFinPedido
     *
     * @return  self
     */ 
    public function setFechaHoraFinPedido(string $fechaHoraFinPedido) : self
    {
        $this->fechaHoraFinPedido = $fechaHoraFinPedido;

        return $this;
    }

    public function jsonSerialize() : mixed
    {
        $ret = array();
        $ret["id"] = $this->id;
        $ret["codigo"] = $this->codigo;
        $ret["cantidad"] = $this->cantidad;
        $ret["clienteId"] = ( $this->cliente !== NULL ) ? $this->cliente->getId() : -1;
        $ret["empleadoId"] = ( $this->empleadoTomaPedido !== NULL ) ? $this->empleadoTomaPedido->getId() : -1;
        $ret["productoId"] = ( $this->producto !== NULL ) ? $this->producto->getId() : -1;
        $ret["mesaId"] = ( $this->mesa !== NULL ) ? $this->mesa->getId() : -1;
        $ret["estadoId"] = ( $this->estado !== NULL ) ? $this->estado->getId() : -1;
        $ret["imgPath"] = $this->imgPath;
        $ret["fechaHoraInicioPedido"] = $this->fechaHoraInicioPedido;
        $ret["fechaHoraFinPedidoEstipulada"] = $this->fechaHoraFinPedidoEstipulada;
        $ret["fechaHoraFinPedido"] = $this->fechaHoraFinPedido;
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
        catch ( JsonException ) {
            return NULL;
        }
        
    }

    private static function asssocToObj( array $assoc ) : ?self {
        $keysHasToHave = array_keys( (new Pedido())->jsonSerialize() );
        $keysHasHave = array_keys( $assoc );
        
        if ( count( array_diff( $keysHasToHave, $keysHasHave ) ) > 0 ) return NULL;

        $id = intval($assoc["id"]);
        $cantidad = intval($assoc["cantidad"]);
        $cliente = intval($assoc["clienteId"]);
        $empleado = intval($assoc["empleadoTomaPedidoId"]);
        $producto = intval($assoc["productoId"]);
        $estado = intval($assoc["estadoId"]);
        $ret = new Pedido(  $id,
                            $assoc["codigo"],
                            $cantidad,
                            new Usuario($cliente),
                            new Usuario($empleado),
                            new Producto($producto),
                            new Mesa($assoc["mesaId"]),
                            new PedidoEstado($estado),
                            $assoc["imgPath"],
                            $assoc["fechaHoraInicioPedido"],
                            $assoc["fechaHoraFinPedidoEstipulada"],
                            $assoc["fechaHoraFinPedido"]
        );

        return $ret;
    }

}