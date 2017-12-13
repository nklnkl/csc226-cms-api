<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

interface Resource_Router {
  public function create (Request $request, Response $response);
  public function retrieve (Request $request, Response $response);
  public function update (Request $request, Response $response);
  public function delete (Request $request, Response $response);
  public function list (Request $request, Response $response);
}
?>
