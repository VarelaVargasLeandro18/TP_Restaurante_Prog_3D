<?php

/**
 * Representa el permiso que tiene un Empleado para cambiar estado de un Pedido según el Sector en el que esté. 
 */
class PermisosEmpleadoSector implements JsonSerializable
{

    private int $id;
    private ?TipoUsuario $tipo;
    private ?Sector $sector;
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
        $ret["sectorId"] = ( $this->sector !== NULL ) ? $this->sector->getId() : -1;
        $ret["permisos"] = $this->permisos;
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
        $keysHasToHave = array_keys( (new PermisosEmpleadoSector())->jsonSerialize() );
        $keysHasHave = array_keys( $assoc );
        
        if ( count( array_diff( $keysHasToHave, $keysHasHave ) ) > 0 ) return NULL;

        $id = intval($assoc['id']);
        $sector = intval($assoc['sectorId']);
        $tipo = intval($assoc["tipoId"]);
        $ret = new PermisosEmpleadoSector(
                                            $id,
                                            new TipoUsuario($tipo),
                                            new Sector($sector),
                                            $assoc['permisos']
        );

        return $ret;
    }

}
