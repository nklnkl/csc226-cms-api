<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Interop\Container\ContainerInterface as ContainerInterface;

class Comment_Service implements Resource_Router {
  protected $container;
  protected $db;

  // constructor receives container instance
  public function __construct(ContainerInterface $container) {
    $this->container = $container;
    $this->db = $container->get('db');
  }

  public function create (Request $request, Response $response) {
    $data = array('name' => 'niko', 'age' => 25);
    $newResponse = $response->withJson($data);
    return $newResponse;
  }
  public function retrieve (Request $request, Response $response) {
    $response = $response->withStatus(501);
    return $response;
  }
  public function update (Request $request, Response $response) {
    $response = $response->withStatus(501);
    return $response;
  }
  public function delete (Request $request, Response $response) {
    $response = $response->withStatus(501);
    return $response;
  }
  public function list (Request $request, Response $response) {
    $response = $response->withStatus(501);
    return $response;
  }
}

?>
