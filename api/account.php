<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Interop\Container\ContainerInterface as ContainerInterface;

class Account_Service {

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
    $sql = "
    INSERT INTO accounts
      (id,
      created,
      updated,
      status,
      email,
      password,
      username,
      bio,
      location,
      role)
    VALUES
      ('$id',
      '$created',
      '$updated',
      '$status',
      '$email',
      '$password',
      '$username',
      '$bio',
      '$location',
      '0')
    ";

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

    $targetId = $request->getAttribute('id');

    // Try SQL.
    $statement = NULL;
    try {
      $sql = "
      SELECT * FROM accounts
      WHERE
        id = '$targetId'
      LIMIT 1
      ";
      $statement = $this->db->query($sql);
    } catch (PDOexception $e) {
      // If internal database error, return early.
      $response = $response->withStatus(500);
      return $response;
    }

    // If nothing found, return early.
    if ($statement->rowCount() == 0) {
      $response = $response->withStatus(404);
      return $response;
    }

    // Get account if found.
    $account = $statement->fetch();

    // Remove password hash from result.
    unset($account['password']);

    // If account is inactive and not admin, return early.
    if ($account['status'] == 1 && $request->getAttribute('role') != 1) {
      $response = $response->withStatus(410);
      return $response;
    }

    // If client does not have session, remove email from result.
    if (!$request->getAttribute('session'))
      unset($account['email']);

    // If client does not have ownership of resource AND not admin, remove email from result.
    $owner = ($request->getAttribute('id') == $request->getHeader('account-id')[0]);
    if (!$owner && $request->getAttribute('role') != 1)
      unset($account['email']);

    // Success!
    $response = $response->withJson($account);
    return $response;

  }

  public function update (Request $request, Response $response) {

    // Check session
    if (!$request->getAttribute('session')) {
      $response = $response->withStatus(401);
      return $response;
    }

    // If client is not owner AND not admin, return early.
    $owner = ($request->getAttribute('id') == $request->getHeader('account-id')[0]);
    if ( !$owner && $request->getAttribute('role') != 1 ) {
      $response = $response->withStatus(403);
      return $response;
    }

    // Parse update body.
    $update = $request->getParsedBody();

    // Set current time for updated.
    $updated = time();
    // Get email from body.
    $email = $update['email'];
    // Get password from body and hash.
    $password = $update['password'];
    if ($password)
      $password = password_hash($password, PASSWORD_DEFAULT);
    // Get username from body.
    $username = $update['username'];
    // Get bio from body.
    $bio = $update['bio'];
    // Get location from body.
    $location = $update['location'];
    // Don't get status, this will be done after admin validation.
    $status = NULL;

    // Only allow status update if admin.
    if ($request->getAttribute('role') == 1) {
      $status = $update['status'];
    }

    // Try Update SQL.\
    $targetId = $request->getAttribute('id');
    $statement = NULL;
    try {
      $sql = "
      UPDATE accounts
      SET updated = '$updated',
      email = IF('$email' = '', email, '$email'),
      password = IF('$password' = '', password, '$password'),
      username = IF('$username' = '', username, '$username'),
      bio = IF('$bio' = '', bio, '$bio'),
      location = IF('$location' = '', location, '$location'),
      status = IF('$status' IS NOT null, status, '$status')
      WHERE id = '$targetId'
      ";
      $statement = $this->db->query($sql);
    } catch (PDOexception $e) {
      // If duplicate error occurs, return early.
      if ($e->getCode() == '23000') {
        $response = $response->withStatus(409);
        return $response;
      }
      // If internal db error occurs, return early.
      $response = $response->withStatus(500);
      return $response;
    }

    // If nothing found.
    if ($statement->rowCount() == 0) {
      $response = $response->withStatus(404);
      return $response;
    }

    return $response;
  }

  public function delete (Request $request, Response $response) {

    // Check session
    if (!$request->getAttribute('session')) {
      $response = $response->withStatus(401);
      return $response;
    }

    // If client is not owner AND not admin, return early.
    $owner = ($request->getAttribute('id') == $request->getHeader('account-id')[0]);
    if ( !$owner && $request->getAttribute('role') != 1 ) {
      $response = $response->withStatus(403);
      return $response;
    }

    // Try SQL.
    $targetId = $request->getAttribute('id');
    $statement = NULL;
    try {
      $sql = "
      UPDATE accounts
      SET status = 1
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

    // Get username from param query.
    $username = $request->getQueryParam('username', $default = NULL);
    // Get list pagination, default to 0 if NULL.
    $page = $request->getQueryParam('page', $default = 0);
    // Get list size.
    $size = 10;
    // Default offset to null.
    $offset = ($size * $page);

    // Default statement to NULL.
    $statement = NULL;

    // Try SQL with query param, by like username, limited.
    if ($username) {
      try {
        $sql = "
        SELECT id, username
        FROM accounts
        WHERE
          username LIKE '$username%'
        LIMIT $offset, $size
        ORDER BY username
        ";
        $statement = $this->db->query($sql);
      } catch (PDOexception $e) {
        // If internal database error, return early.
        $response = $response->withStatus(500);
        return $response;
      }
    }
    // Try SQL without query param, get all, limited.
    else {
      try {
        $sql = "
        SELECT id, username
        FROM accounts
        ORDER BY created
        LIMIT $offset, $size
        ";
        $statement = $this->db->query($sql);
      } catch (PDOexception $e) {
        // If internal database error, return early.
        throw($e);
        $response = $response->withStatus(500);
        return $response;
      }
    }

    // If nothing found, return early.
    if ($statement->rowCount() == 0) {
      $response = $response->withStatus(404);
      return $response;
    }

    // Get rows as list array.
    $list = $statement->fetchAll();

    // Success!
    $response = $response->withJson($list);
    return $response;

  }
}

?>
