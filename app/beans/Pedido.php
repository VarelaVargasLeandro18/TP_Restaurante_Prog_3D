<?php

/**
 * Representa un pedido realizado por un cliente
 */
class Pedido implements JsonSerializable
{

    private string $id;
    private Producto $producto;
    private Mesa $mesa;
    private PedidoEstado $estado;

    public function __construct(
                                string $id,
                                Producto $producto,
                                Mesa $mesa,
                                PedidoEstado $estado)
    {
        $this->id = $id;
        $this->producto = $producto;
        $this->mesa = $mesa;
        $this->estado = $estado;
    }

    /**
     * Get the value of id
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of producto
     */
    public function getProducto(): Producto
    {
        return $this->producto;
    }

    /**
     * Set the value of producto
     *
     * @return  self
     */
    public function setProducto(Producto $producto): self
    {
        $this->producto = $producto;

        return $this;
    }

    /**
     * Get the value of mesa
     */
    public function getMesa(): Mesa
    {
        return $this->mesa;
    }

    /**
     * Set the value of mesa
     *
     * @return  self
     */
    public function setMesa(Mesa $mesa): self
    {
        $this->mesa = $mesa;

        return $this;
    }

    /**
     * Get the value of estado
     */
    public function getEstado(): PedidoEstado
    {
        return $this->estado;
    }

    /**
     * Set the value of estado
     *
     * @return  self
     */
    public function setEstado(PedidoEstado $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function jsonSerialize() : mixed
    {
        $ret = array();
        $ret["id"] = $this->id;
        $ret["producto"] = $this->producto->jsonSerialize();
        $ret["mesa"] = $this->mesa->jsonSerialize();
        $ret["estado"] = $this->estado->jsonSerialize();
        return $ret;
    }

}