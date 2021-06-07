<?php

namespace interfaces;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;

/**
 * Define las operaciones CRUD a realizar según un HttpRequest.
 */
interface ICRUDApi
{
	/**
     * Lee un objeto según el 'id' en $args.
     */
	public static function read (Request $request, Response $response, Array $args) : Response;
	
	/**
	 * Lee todos los objetos.
	 */
	public static function readAll (Request $request, Response $response, Array $args) : Response;
	
	/**
	 * Inserta un objeto pasado por POST como JSON.
	 */
	public static function insert (Request $request, Response $response, Array $args) : Response;
	
	/**
	 * Borra un objeto según el 'id' en $args.
	 */
	public static function delete (Request $request, Response $response, Array $args) : Response;
	public static function update (Request $request, Response $response, Array $args) : Response;
}
