<?php

require_once __DIR__ . '/../interfaces/ICRUD.php';
require_once __DIR__ . '/../db/AccesoDatos.php';

abstract class AbstractCRUD //implements ICRUD 
{

    /**
     * Nombre de la tabla.
     */
    private string $tabla;
    
    /**
     * Array que contiene nombre de columnas de la siguiente manera:
     * ['nombreColumna' => 'TIPOPDO']
     */
    private array $columnas;

    /**
     * String con el nombre de la columna PRIMARY_KEY
     */
    private string $columnaId;

    protected function __construct( string $tabla, array $columnas, string $columnaId )
    {
        $this->tabla = $tabla;
        $this->columnas = $columnas;
        $this->columnaId = $columnaId;
    }

    #region GETTERS
    /**
     * Get nombre de la tabla.
     */ 
    protected function getTabla()
    {
        return $this->tabla;
    }

    /**
     * Get array que contiene nombre de columnas.
     */ 
    protected function getColumnas()
    {
        return $this->columnas;
    }    

    /**
     * Get string con el nombre de la columna PRIMARY_KEY
     */
    protected function getColumnaId ()
    {
        return $this->columnaId;
    }
    #endregion GETTERS

    #region CRUD
    /**
     * Inserta los valores dados.
     * @param valores Par columna/valor.
     * @return bool 'true' si se crea, 'false' si no.
     */
    protected final function create ( array $valores ) : bool {
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta( $this->armarInsert( $valores ) );
        $this->bindParams( $valores, $statement );
        return $statement->execute();
    }

    /**
     * Lee los valores de las columnas dadas.
     * @param valores Par columna/valor o simplemente array de columnas.
     * @return array Filas SQL como array asociativo.
     */
    protected final function readAll ( array $valores ) : array {
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta( $this->armarSelect( $valores ) . ';' );
        $statement->execute();
        return $statement->fetchAll( PDO::FETCH_ASSOC );
    }

    /**
     * Lee los valores de las columnas dadas por Id.
     * @param valores Par columna/valor o simplemente array de columnas.
     * @param Id Id de la FILA a leer.
     * @return array Fila SQL como array asociativo.
     */
    protected final function readById ( array $valores, mixed $Id ) : array {
        $access = AccesoDatos::obtenerInstancia();
        $query = $this->armarSelect( $valores ) . ' ' . $this->agregarWhereId();
        $statement = $access->prepararConsulta( $query );
        $this->bindParams( array ( $this->columnaId => $Id ), $statement );
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);        
    }

    /**
     * Actualiza el valor de la fila con el Id dado.
     * @param valores Par columna/valor. DEBE INCLUIR ID.
    */
    protected final function updateById ( array $valores ) : bool {
        $access = AccesoDatos::obtenerInstancia();
        $query = $this->armarUpdate( $valores );
        $statement = $access->prepararConsulta( $query );
        $this->bindParams( $valores, $statement );
        return $statement->execute();
    }

    /**
     * Borra una fila con el Id dado.
     * @param Id Id de la FILA a borrar.
     * @return array Fila SQL como array asociativo.
    */
    protected final function deleteById ( mixed $Id ) : array {
        $readed = $this->readById( $this->columnas, $Id );

        if ( count($readed) === 0 ) return $readed;

        $access = AccesoDatos::obtenerInstancia();
        $query = $this->armarDelete();
        $statement = $access->prepararConsulta($query);
        $this->bindParams( array ( 'id' => $Id ), $statement );
        
        if ( $statement->execute() ) return $readed;

        return array();
    }
    #endregion CRUD

    /**
     * Ejecuta una query SQL determinada por el usuario.
     * @param query Consulta SQL.
     * @param valores Par clave/valor de parámetros SQL presentes en la query.
     * @return array Filas SQL como array asociativo.
     */
    protected final function query ( string $query, array $valores ) : array {
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta($query);
        $this->bindParams( $valores, $statement );
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    #region Funciones Privadas
    /**
     * Genera un string con la consulta INSERT SQL.
     * @param arr Par columna/valor.
     */
    private function armarInsert ( array $arr ) : string {
        $query = 'INSERT INTO ';
        $query .= $this->tabla;
        $query .= ' (';

        $query .= $this->agregarColumnas($arr);
            
        $query[ strrpos($query, ',') ] = ')';
        $query .= ' VALUES (';
        
        $query .= $this->agregarBindings($arr);
        
        $query[ strrpos($query, ',') ] = ')';
        $query .= ';';
        
        return $query;
    }

    /**
     * Genera un string con la consulta UPDATE SQL.
     * @param arr Par columna/valor. DEBE CONTENER COLUMNA PRIMARY KEY.
     */
    private function armarUpdate ( array $arr ) : string {
        $query = 'UPDATE ';
        $query .= $this->tabla . ' SET ';
        $query .= $this->agregarSet( $arr );
        $query[ strrpos($query, ',') ] = ' ';
        $query .= $this->agregarWhereId();
        $query .= ';';
        return $query;
    }

    /**
     * Genera un string con la consulta DELETE SQL.
    */
    private function armarDelete () : string {
        $query = 'DELETE FROM ' . $this->tabla . ' WHERE ';
        $query .= $this->columnaId . ' = ' . ':' . $this->columnaId;
        return $query;
    }

    /**
     * Genera un string con la consulta SELECT.
     */
    private function armarSelect ( array $arr ) : string {
        $query = 'SELECT ';
        $query .= $this->agregarColumnas( $arr );
        $query[ strrpos($query, ',') ] = ' ';
        $query .= ' FROM ' . $this->tabla;
        return $query;
    }

    /**
     * Genera un string con las columnas separadas por ','
     * @param arr Array con columnas. Puede ser par Clave/Valor o solo Columnas.
     */
    private function agregarColumnas ( array $arr ) : string {
        $ret = '';
        
        if(array_keys($arr) === range(0, count($arr) - 1))
            $columns = $arr;
        else
            $columns = array_keys($arr);

        
        foreach ( $columns as $col )
            $ret .= $col . ',';
        
        return $ret;
    }

    /**
     * Genera un string con las variables SQL separadas por ','
     */
    private function agregarBindings ( array $arr ) : string {
        $ret = '';
        $columns = array_keys($arr);
        
        foreach ( $columns as $col )
            $ret .= ':' . $col . ',';
        
        return $ret;
    }

    /**
     * Arma el SET del SQL UPDATE, igualando cada columna con el bind correspondiente.
    */
    private function agregarSet ( array $arr ) : string {
        $ret = '';
        $columns = array_keys($arr);
        $idIndex = array_search( $this->columnaId, $columns );
        
        if ( $idIndex !== false ) unset($columns[$idIndex]);

        foreach ( $columns as $col ) {
            $ret .= $col . ' = ' . ':' . $col . ',';
        }
        
        return $ret;
    }

    /**
     * Armar el WHERE de SQL con el Id.
     */
    private function agregarWhereId () : string {
        $ret = 'WHERE ' . $this->columnaId . ' = :' . $this->columnaId;
        return $ret;
    }

    /**
     * Hace un Bind de cada parámetro SQL con su respectivo valor.
     */
    private function bindParams ( array $arr, PDOStatement &$statement ) {
        
        foreach ( $arr as $column => $value ) {
            $paramSQL = ':' . $column;
            $tipoColumna = $this->columnas[$column];

            if ( $value !== NULL ) {
                $statement->bindValue( $paramSQL, $value, $tipoColumna );
                continue;
            }

            $statement->bindValue( $paramSQL, $value, PDO::PARAM_NULL );

        }

    }
    #endregion Funciones Privadas
    
}