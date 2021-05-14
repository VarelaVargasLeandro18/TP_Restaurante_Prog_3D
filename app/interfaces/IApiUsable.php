<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

interface CRUDApi
{
	public function read (Request $request, Response $response, Array $args) : mixed;
	public function readAll (Request $request, Response $response, Array $args) : Array;
	public function insert (Request $request, Response $response, Array $args) : void;
	public function delete (Request $request, Response $response, Array $args) : mixed;
	public function update (Request $request, Response $response, Array $args) : void;
}
