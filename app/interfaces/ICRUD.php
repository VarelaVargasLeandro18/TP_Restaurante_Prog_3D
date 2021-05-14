<?php

/**
 * Define las operaciones CRUD a realizar en la BBDD.
 */
interface ICRUD
{
	/**
	 * Trae un objeto según el ID del mismo.
	 * 
	 * @return mixed Objeto pedido.
	 */
	public static function readById (mixed $id) : mixed;

	/**
	 * Lee todos los  objetos.
	 * 
	 * @return Array Objetos.
	 */
	public static function readAllObjects () : Array;

	/**
	 * Inserta un objeto.
	 */
	public static function insertObject (mixed $obj) : void;

	/**
	 * Borra un objeto basado en su ID.
	 * 
	 * @return mixed Objeto Borrado.
	 */
	public static function deleteById (mixed $id) : mixed;

	/**
	 * Actualiza el objeto.
	 * 
	 * @return bool True si se actualizó correctamente. False caso contrario.
	 */
	public static function updateObject (mixed $obj) : bool;

	/**
	 * Lee un objeto en base a sus parametros.
	 * 
	 * @return mixed Objeto leido.
	 */
	public static function readByParams ( array $params ) : mixed;

}
