<?php

namespace interfaces;

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
	public function readById (mixed $id) : mixed;

	/**
	 * Lee todos los  objetos.
	 * 
	 * @return Array Objetos.
	 */
	public function readAllObjects () : Array;

	/**
	 * Inserta un objeto.
	 */
	public function insertObject (mixed $obj) : mixed;

	/**
	 * Borra un objeto basado en su ID.
	 * 
	 * @return mixed Objeto Borrado.
	 */
	public function deleteById (mixed $id) : mixed;

	/**
	 * Actualiza el objeto.
	 * 
	 * @return bool True si se actualizó correctamente. False caso contrario.
	 */
	public function updateObject (mixed $obj) : bool;

}
