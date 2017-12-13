<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

interface ResourceRouter {
  public static function create (Request $request, Response $response);
  public static function retrieve (Request $request, Response $response);
  public static function update (Request $request, Response $response);
  public static function delete (Request $request, Response $response);
  public static function list (Request $request, Response $response);
}
?>
