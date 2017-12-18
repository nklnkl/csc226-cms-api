<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Interop\Container\ContainerInterface as ContainerInterface;

class Session_Service {
  protected $container;
  protected $db;

  // constructor receives container instance
  public function __construct(ContainerInterface $container) {
    $this->container = $container;
    $this->db = $container->get('db');
  }

  public function create (Request $request, Response $response) {
    // Parse body.
    $login = $request->getParsedBody();

    // VALIDATE LOGIN

    // If login is missing.
    if(!$login['password'] || !$login['email']) {
      $response = $response->withStatus(401);
      return $response;
    }

    // Input.
    $email = $login['email'];
    $password = $login['password'];

    // SQL.
    $statement = NULL;
    try {
      $sql = "SELECT id, email, password, status FROM accounts WHERE email = '$email' LIMIT 1";
      $statement = $this->db->query($sql);
    } catch (PDOexception $e) {
      $response = $response->withStatus(500);
      return $response;
    }

    // If no account found.
    if ($statement->rowCount() == 0) {
      $response = $response->withStatus(401);
      return $response;
    }

    // If password does not match.
    $account = $statement->fetch();
    if (!password_verify($password, $account['password'])) {
      $response = $response->withStatus(401);
      return $response;
    }

    // If the account is inactive.
    if ($account['status'] == 1) {
      $response = $response->withStatus(403);
      return $response;
    }

    // CREATE SESSION

    // Input.
    $id = uniqid();
    $created = time();
    $updated = $created;
    $account_id = $account['id'];

    // SQL.
    $statement = NULL;
    try {
      $sql = "
      INSERT INTO sessions
        (id, created, updated, account_id)
      VALUES
        ('$id', '$created', '$updated', '$account_id')
      ";
      $statement = $this->db->query($sql);
    } catch (PDOexception $e) {
      $response = $response->withStatus(500);
      return $response;
    }

    // Success!
    $session = array('session_id' => $id, 'account_id' => $account['id']);
    $response = $response->withJson($session);
    return $response;
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

    // If not valid session, return early.
    if (!$request->getAttribute('session')) {
      $response = $response->withStatus(401);
      return $response;
    }

    // Try SQL to check ownership of session.
    $targetId = $request->getAttribute('id');
    $statement = NULL;
    try {
      $sql = "
      SELECT * FROM sessions
      WHERE
        id = '$targetId'
      LIMIT 1
      ";
      $statement = $this->db->query($sql);
    } catch (PDOexception $e) {
      $response = $response->withStatus(500);
      return $response;
    }
    // If nothing was found.
    if ($statement->rowCount() == 0) {
      $response = $response->withStatus(404);
      return $response;
    }

    // Get session if found.
    $session = $statement->fetch();

    // If client is not owner AND not admin, return early.
    $owner = ( $session['account_id'] == $request->getHeader('account_id')[0] );
    if ( !$owner && $request->getAttribute('role') != 1 ) {
      $response = $response->withStatus(403);
      return $response;
    }

    // Try SQL.
    $targetId = $request->getAttribute('id');
    $statement = NULL;
    try {
      $sql = "
      DELETE FROM sessions
      WHERE
        id = '$targetId'
      LIMIT 1
      ";
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

    // Success!
    $response = $response->withStatus(200);
    return $response;
  }
  public function list (Request $request, Response $response) {
    $response = $response->withStatus(501);
    return $response;
  }
}

?>
