<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Define las operaciones CRUD a realizar según un HttpRequest.
 */
interface ICRUDApi
{
	public static function read (Request $request, Response $response, Array $args) : Response;
	public static function readAll (Request $request, Response $response, Array $args) : Response;
	public static function insert (Request $request, Response $response, Array $args) : Response;
	public static function delete (Request $request, Response $response, Array $args) : Response;
	public static function update (Request $request, Response $response, Array $args) : Response;
}
