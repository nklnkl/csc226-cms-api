<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Interop\Container\ContainerInterface as ContainerInterface;

class Blog_Post_Service implements Resource_Router {
  protected $container;
  protected $db;

  // constructor receives container instance
  public function __construct(ContainerInterface $container) {
    $this->container = $container;
    $this->db = $container->get('db');
  }

  public function create (Request $request, Response $response) {

    // If not valid session, return early.
    if (!$request->getAttribute('session')) {
      $response = $response->withStatus(401);
      return $response;
    }

    // If client account is inactive
    if ($request->getAttribute('status') == 1) {
      $response = $response->withStatus(403);
      return $response;
    }

    // Parse body.
    $blog_post = $request->getParsedBody();

    // Validate input.
    if (!$blog_post['title'] || !$blog_post['body'] || !$blog_post['privacy']) {
      $response = $response->withStatus(422);
      return $response;
    }

    // Prepare input.
    $id = uniqid();
    $created = time();
    $updated = $created;
    $account_id = $request->getHeader('account-id');
    $title = $blog_post['title'];
    $body = $blog_post['body'];
    $privacy = $blog_post['privacy'];

    // SQL.
    $sql = "
    INSERT INTO blog_posts
      (id,
      created,
      updated,
      account_id,
      title,
      body,
      privacy)
    VALUES
      ('$id',
      '$created',
      '$updated',
      '$account_id',
      '$title',
      '$body',
      '$privacy')
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

    // Try SQL.
    $statement = NULL;
    try {
      $sql = "
      SELECT * FROM blog_posts
      WHERE
        id = '$request->getAttribute('id')'
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

    // Get blog_post if found.
    $blog_post = $statement->fetch();

    // If blog_post is private, not owned, client is not admin.
    if ($blog_post['privacy'] == 1
        && $request->getHeader('account-id') != $blog_post['account_id']
        && $request->getAttribute('role') != 1) {
      $response = $response->withStatus(403);
      return $response;
    }

    // Success!
    $response = $response->withJson($blog_post);
    return $response;
  }
  public function update (Request $request, Response $response) {

    // Check session
    if (!$request->getAttribute('session')) {
      $response = $response->withStatus(401);
      return $response;
    }

    // If not admin, Try sql query to check ownership of blog post.
    $statement = NULL;
    try {
      $sql = "
      SELECT * FROM blog_posts
      WHERE
        id = '$request->getAttribute('id')'
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

    // Get blog_post if found.
    $blog_post = $statement->fetch();

    // If blog_post is private, not owned, client is not admin.
    if ($request->getHeader('account-id') != $blog_post['account_id']
        && $request->getAttribute('role') != 1) {
      $response = $response->withStatus(403);
      return $response;
    }$response;
    }

    // Parse update body.
    $update = $request->getParsedBody();

    $updated = $created;
    $title = $blog_post['title'];
    $body = $blog_post['body'];
    $privacy = $blog_post['privacy'];

    // Try Update SQL.
    $statement = NULL;
    try {
      $sql = "
      UPDATE blog_posts
      SET updated = $updated,
      SET title = CASE
        WHEN $title THEN $title ELSE title END,
      SET body = CASE
        WHEN $body THEN $body ELSE body END,
      SET privacy = CASE
        WHEN $privacy THEN $privacy ELSE privacy END
      WHERE id = '$request->getAttribute('id')'
      LIMIT 1
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
    $response = $response->withStatus(501);
    return $response;
  }
  public function list (Request $request, Response $response) {
    $response = $response->withStatus(501);
    return $response;
  }
}

?>
