<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Define las operaciones CRUD a realizar según un HttpRequest.
 */
interface ICRUDApi
{
	public function read (Request $request, Response $response, Array $args) : Response;
	public function readAll (Request $request, Response $response, Array $args) : Response;
	public function insert (Request $request, Response $response, Array $args) : Response;
	public function delete (Request $request, Response $response, Array $args) : Response;
	public function update (Request $request, Response $response, Array $args) : Response;
}
