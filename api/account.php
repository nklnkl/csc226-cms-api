<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Interop\Container\ContainerInterface as ContainerInterface;

class Account_Service implements Resource_Router {

  protected $container;
  protected $db;

  // constructor receives container instance
  public function __construct(ContainerInterface $container) {
    $this->container = $container;
    $this->db = $container->get('db');
  }

  public function create (Request $request, Response $response) {
    // Parse body.
    $account = $request->getParsedBody();

    // Validate input.
    if (!$account['username'] || !$account['email'] || !$account['password']) {
      $response = $response->withStatus(422);
      return $response;
    }

    // Input.
    $id = uniqid();
    $created = time();
    $updated = $created;
    $status = 0;
    $email = $account['email'];
    $password = password_hash($account['password'], PASSWORD_DEFAULT);
    $username = $account['username'];
    $bio = '';
    $location = '';

    // SQL.
    $sql = "INSERT INTO accounts (id, created, updated, status, email,
    password, username, bio, location)
    VALUES ('$id', '$created', '$updated', '$status', '$email', '$password',
    '$username', '$bio', '$location')";

    // Try to execute sql.
    $statement = NULL;
    try {
      $statement = $this->db->query($sql);
    } catch (PDOException $e) {
      if ($e->getCode() == '23000') {
        $response = $response->withStatus(409);
        return $response;
      }
      $response = $response->withStatus(500);
      return $response;
    }

    // Success!
    return $response;
  }

  public function retrieve (Request $request, Response $response) {

    // Get target id.
    $id = $request->getAttribute('id');

    // Try SQL.
    $statement = NULL;
    try {
      $sql = "SELECT * FROM accounts WHERE id = '$id' LIMIT 1";
      $statement = $this->db->query($sql);
    } catch (PDOexception $e) {
      $response = $response->withStatus(500);
      return $response;
    }

    // If nothing found.
    if ($statement->rowCount() == 0) {
      $response = $response->withStatus(404);
      return $response;
    }

    // If account is inactive.
    $account = $statement->fetch();
    if ($account['status'] == 1) {
      $response = $response->withStatus(410);
      return $response;
    }

    // Remove private data from response.
    unset($account['password']);
    unset($account['status']);

    // Remove email from response if not valid session or if not owned.
    if ($request->getAttribute('session') == false)
      unset($account['email']);
    if ($id != $request->getHeader('account-id'))
      unset($account['email']);

    // Success!
    $response = $response->withJson($account);
    return $response;

  }

  public function update (Request $request, Response $response) {

    // Check session
    if ($request->getAttribute('session') == false) {
      $response = $response->withStatus(401);
      return $response;
    }

    // Get target id and check for ownership.
    if ($request->getAttribute('id') != $request->getHeader('account-id')) {
      $response = $response->withStatus(403);
      return $response;
    }

    // Get target id.
    $id = $request->getAttribute('id');

    // Parse body.
    $account = $request->getParsedBody();

    // Input.
    $updated = time();
    $status = 0;
    $email = $account['email'];
    $password = password_hash($account['password'], PASSWORD_DEFAULT);
    $username = $account['username'];
    $bio = '';
    $location = '';

    // Try SQL.
    $statement = NULL;
    try {
      $sql = "SELECT * FROM accounts WHERE id = '$id' LIMIT 1";
      $statement = $this->db->query($sql);
    } catch (PDOexception $e) {
      $response = $response->withStatus(500);
      return $response;
    }

    // If nothing found.
    if ($statement->rowCount() == 0) {
      $response = $response->withStatus(404);
      return $response;
    }

    // If account is inactive.
    $account = $statement->fetch();
    if ($account['status'] == 1) {
      $response = $response->withStatus(410);
      return $response;
    }
  }

  public function delete (Request $request, Response $response) {

    // Check session
    if ($request->getAttribute('session') == false) {
      $response = $response->withStatus(401);
      return $response;
    }

    // Get target id and check for ownership.
    if ($request->getAttribute('id') != $request->getHeader('account-id')) {
      $response = $response->withStatus(403);
      return $response;
    }

    // Get target id.
    $id = $request->getAttribute('id');

    // Try SQL.
    $statement = NULL;
    try {
      $sql = "UPDATE accounts SET status = 1 WHERE id = '$id' LIMIT 1";
      $statement = $this->db->query($sql);
    } catch (PDOexception $e) {
      $response = $response->withStatus(500);
      return $response;
    }

    // If nothing was affected.
    if ($statement->rowCount() == 0) {
      $response = $response->withStatus(404);
      return $response;
    }

    $response = $response->withStatus(200);
    return $response;
    
  }
  public function list (Request $request, Response $response) {
    $response = $response->withStatus(501);
    return $response;
  }
}

?>
