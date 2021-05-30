<?php

require_once __DIR__ . '/../interfaces/ICRUD.php';
require_once __DIR__ . '/../db/AccesoDatos.php';

/*abstract*/ class AbstractCRUD //implements ICRUD 
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

    public function __construct( string $tabla, array $columnas, string $columnaId )
    {
        $this->tabla = $tabla;
        $this->columnas = $columnas;
        $this->columnaId = $columnaId;
    }

    #region GETTERS
    /**
     * Get nombre de la tabla.
     */ 
    public function getTabla()
    {
        return $this->tabla;
    }

    /**
     * Get array que contiene nombre de columnas.
     */ 
    public function getColumnas()
    {
        return $this->columnas;
    }    

    public function getColumnaId ()
    {
        return $this->columnaId;
    }
    #endregion GETTERS

    /**
     * Inserta los valores dados.
     * @param valores Par columna/valor.
     */
    public final function create ( array $valores ) {
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta( $this->armarInsert( $valores ) );
        $this->bindParams( $valores, $statement );
        return $statement->execute();
    }

    /**
     * Lee los valores de las columnas dadas.
     * @param valores Par columna/valor.
     */
    public final function readAll ( array $valores ) {
        $access = AccesoDatos::obtenerInstancia();
        $statement = $access->prepararConsulta( $this->armarSelect( $valores ) . ';' );
        $statement->execute();
        return $statement->fetchAll( PDO::FETCH_ASSOC );
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
        $query .= $this->agregarWhere($arr);
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
    private function armarSelect () : string {
        $query = 'SELECT ';
        $query .= $this->agregarColumnas( $this->columnas );
        $query .= ' FROM ' . $this->tabla;
        return $query;
    }

    /**
     * Genera un string con las columnas separadas por ','
     */
    private function agregarColumnas ( array $arr ) : string {
        $ret = '';
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
        unset($columns[$idIndex]);

        foreach ( $columns as $col ) {
            $ret .= $col . ' = ' . ':' . $col . ',';
        }
        
        return $ret;
    }

    private function agregarWhere ( array $arr ) : string {
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