<?php

require_once __DIR__ . '/../beans/Empleado.php';
require_once __DIR__ . '/../interfaces/ICRUD.php';
require_once __DIR__ . '/../db/AccesoDatos.php';
require_once __DIR__ . '/SectorModel.php';
require_once __DIR__ . '/TipoEmpleadoModel.php';
require_once __DIR__ . '/PermisosEmpleadoSectorModel.php';

class EmpleadoModel implements ICRUD {

    private static string $nombreTabla = 'Empleado';
    private static string $columnaId = 'Id';
    private static string $columnaNombre = 'Nombre';
    private static string $columnaApellido = 'Apellido';
    private static string $columnaSectorId = 'SectorId';
    private static string $columnaTipoEmpleadoId = 'TipoEmpleadoId';
    private static string $columnaUsuario = 'Usuario';
    private static string $columnaContraseniaHash = 'Contrasenia_hash';
    private static string $columnaFechaIngreso = 'FechaIngreso';
    private static string $columnaCantidadOperaciones = 'CantidadOperaciones';
    private static string $columnaSuspendido = 'Suspendido';


    private static function crearEmpleados(array $allAssoc) : array {
        $ret = array();
        
        foreach ( $allAssoc as $key => $assoc ) {
            $ret [$key] = self::crearEmpleado($assoc);
        }

        return $ret;
    }

    public static function crearEmpleado(array $assoc) : Empleado {
        $ret = NULL;
        $strNow = date('Y-m-d H:i:s');

        $id = intval($assoc[self::$columnaId]);
        $nombre = $assoc[self::$columnaNombre];
        $apellido = $assoc[self::$columnaApellido];

        $sector = null;
        if ( $assoc[self::$columnaSectorId] !== NULL ) {
            $sectorId = intval($assoc[self::$columnaSectorId]);
            $sector = SectorModel::readById($sectorId);
        }

        $tipo = null;
        if ( $assoc[self::$columnaTipoEmpleadoId] !== NULL ) {
            $tipoId = intval($assoc[self::$columnaTipoEmpleadoId]);
            $tipo = TipoEmpleadoModel::readById($tipoId);
        }
        
        $usuario = $assoc[self::$columnaUsuario];
        $contraseniaHash = $assoc[self::$columnaContraseniaHash];
        $fechaIngreso = ($assoc[self::$columnaFechaIngreso] === NULL) ?
                        $strNow : $assoc[self::$columnaFechaIngreso];
        $cantOperaciones = intval($assoc[self::$columnaCantidadOperaciones]);
        $suspendido = boolval($assoc[self::$columnaSuspendido]);

        $ret = new Empleado(
            $id,
            $nombre,
            $apellido,
            $sector,
            $tipo,
            $usuario,
            $contraseniaHash,
            $suspendido
        );
        
        $ret->setFechaIngreso( $fechaIngreso );
        $ret->setCantOperaciones( $cantOperaciones );
        

        return $ret;
    }

    private static function columns () : string {
        $columnaId = self::$columnaId;
        $columnaNombre = self::$columnaNombre;
        $columnaApellido = self::$columnaApellido;
        $columnaSectorId = self::$columnaSectorId;
        $columnaTipoEmpleadoId = self::$columnaTipoEmpleadoId;
        $columnaUsuario = self::$columnaUsuario;
        $columnaContraseniaHash = self::$columnaContraseniaHash;
        $columnaFechaIngreso = self::$columnaFechaIngreso;
        $columnaCantidadOperaciones = self::$columnaCantidadOperaciones;
        $columnaSuspendido = self::$columnaSuspendido;

        $ret = "
                $columnaId,
                $columnaNombre,
                $columnaApellido,
                $columnaSectorId,
                $columnaTipoEmpleadoId,
                $columnaUsuario,
                $columnaContraseniaHash,
                $columnaFechaIngreso,
                $columnaCantidadOperaciones,
                $columnaSuspendido

        ";
        
        return $ret;
    }

    public static function readById(mixed $id): mixed
    {
        $ret = NULL;
        $access = AccesoDatos::obtenerInstancia();
        
        $columns = self::columns();
        $table = self::$nombreTabla;
        $columnaId = self::$columnaId;

        $statement = $access->prepararConsulta(
            "
            SELECT 
                $columns
            FROM $table
            WHERE $columnaId = :id;
            "
        );

        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $executed = $statement->execute();
        
        if ( $executed ) {
            $assoc = $statement->fetch(PDO::FETCH_ASSOC);
            $ret = ($assoc !== false) ? self::crearEmpleado($assoc) : $ret;
        }

        return $ret;
    }

    public static function readAllObjects(): array
    {
        $ret = array();
        $access= AccesoDatos::obtenerInstancia();

        $columns = self::columns();
        
        $statement = $access->prepararConsulta(
            "
            SELECT 
                $columns
            FROM Empleado;
            "
        );
        $executed = $statement->execute();

        if ( $executed ) {
            $allAssoc = $statement->fetchAll(PDO::FETCH_ASSOC);
            $ret = ($allAssoc !== false) ? self::crearEmpleados($allAssoc) : $ret; 
        }

        return $ret;
    }

    public static function insertObject(mixed $obj): bool
    {
        $ret = false;
        $check = self::checkUsrPassAll( $obj->getUsuario(), $obj->getContraseniaHash() ) === NULL;
        
        if ( $check == true ) {
            $contrasenia = password_hash($obj->getContraseniaHash(), PASSWORD_BCRYPT);
            $access = AccesoDatos::obtenerInstancia();
            $columns = self::columns();
            $statement = $access->prepararConsulta(
                "
                INSERT INTO Empleado (
                    $columns
                )
                VALUES (
                    :id,
                    :nombre, 
                    :apellido, 
                    :sectorId, 
                    :tipoId, 
                    :usuario, 
                    :ctrsnia,
                    :fecha,
                    :cantop,
                    :suspendido
                );
                "
            );
            $strNow = date('Y-m-d H:i:s');
            $statement->bindValue(':id', 0, PDO::PARAM_INT);
            $statement->bindValue(':nombre', $obj->getNombre());
            $statement->bindValue(':apellido', $obj->getApellido());
            $statement->bindValue(':usuario', $obj->getUsuario());
            $statement->bindValue(':ctrsnia', $contrasenia );
            $statement->bindValue(':fecha', $strNow);
            $statement->bindValue( ':cantop', 0 );
            $statement->bindValue(':suspendido', $obj->getSuspendido(), PDO::PARAM_BOOL);

            if ( $obj->getSector() ) $statement->bindValue(':sectorId', $obj->getSector()->getId(), PDO::PARAM_INT);
                else $statement->bindValue(':sectorId', null, PDO::PARAM_NULL);

            if ( $obj->getTipo() ) $statement->bindValue(':tipoId', $obj->getTipo()->getId(), PDO::PARAM_INT);
                else $statement->bindValue(':tipoId', null, PDO::PARAM_NULL);

            if ( !$obj->getSector() || !$obj->getTipo() // Si son NULL se ejecuta 
                || 
                PermisosEmpleadoSectorModel::comprobarTipoSector( // Si no son NULL comprobamos que tengan sentido el tipo y el sector y se ejecuta
                $obj->getSector()->getId(),
                $obj->getTipo()->getId()) ) {
                $ret = $statement->execute();
            }
            else
                $ret = false;
        }

        return $ret;
    }

    public static function deleteById(mixed $id): mixed
    {
        $ret = self::readById($id);

        $table = self::$nombreTabla;
        $columnaId = self::$columnaId;

        if ( $ret !== NULL ) {
            $access = AccesoDatos::obtenerInstancia();
            $statement = $access->prepararConsulta(
                "
                DELETE
                FROM $table
                WHERE $columnaId = :id
                "
            );
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->execute();
        }
        return $ret;
    }

    public static function updateObject(mixed $obj): bool
    {
        $ret = false;
        $access = AccesoDatos::obtenerInstancia();

        $table = self::$nombreTabla;
        $columnaId = self::$columnaId;
        $columnaNombre = self::$columnaNombre;
        $columnaApellido = self::$columnaApellido;
        $columnaSector = self::$columnaSectorId;
        $columnaTipo = self::$columnaTipoEmpleadoId;
        $columnaUsuario = self::$columnaUsuario;
        $columnaCtr = self::$columnaContraseniaHash;
        $columnaSuspendido = self::$columnaSuspendido;

        $statement = $access->prepararConsulta(
            "
            UPDATE 
                $table
            SET
                $columnaNombre = :nombre,
                $columnaApellido = :apellido,
                $columnaSector = :sectorId,
                $columnaTipo = :tipoId,
                $columnaUsuario = :usuario,
                /*$columnaCtr = :ctrsnia,*/
                $columnaSuspendido = :suspendido
            WHERE $columnaId = :id;
            "
        );
        $statement->bindValue(':id', $obj->getId(), PDO::PARAM_INT);
        $statement->bindValue(':nombre', $obj->getNombre());
        $statement->bindValue(':apellido', $obj->getApellido());
        $statement->bindValue(':usuario', $obj->getUsuario());
        //$statement->bindValue(':ctrsnia',  password_hash( $obj->getContraseniaHash(), PASSWORD_BCRYPT ) );
        $statement->bindValue(':suspendido', $obj->getSuspendido(), PDO::PARAM_BOOL);

        if ( $obj->getSector() ) $statement->bindValue(':sectorId', $obj->getSector()->getId(), PDO::PARAM_INT);
                else $statement->bindValue(':sectorId', null, PDO::PARAM_NULL);

        if ( $obj->getSector() ) $statement->bindValue(':tipoId', $obj->getTipo()->getId(), PDO::PARAM_INT);
            else $statement->bindValue(':tipoId', null, PDO::PARAM_NULL);
        
        if ( PermisosEmpleadoSectorModel::comprobarTipoSector(
            $obj->getSector()->getId(),
            $obj->getTipo()->getId()) ) {
            $ret = $statement->execute();
        }
        else
            $ret = false;

        return $ret;
    }

    // FUNCIONES PROPIAS
    
    public static function checkUsrPassAll (string $usuario, string $contrasenia) : mixed {
        $ret = NULL;
        $empleados = self::readAllObjects();

        for ( $index = 0; $index < count($empleados); $index++ ) {
            $empleado = $empleados[$index];

            if ( self::checkUsrPass( $empleado, $usuario, $contrasenia ) ){
                $ret = $empleado;
                var_dump($empleado->getId());
                var_dump( password_verify( $contrasenia, $empleado->getContraseniaHash() ) );
                var_dump($contrasenia);
                break;
            }
        }

        return $ret;
    }

    private static function checkUsrPass ( Empleado $empleado, string $usuario, string $contrasenia ) : bool {
        return  password_verify( $contrasenia, $empleado->getContraseniaHash() ) &&
                $empleado->getUsuario() == $usuario;
    }

}