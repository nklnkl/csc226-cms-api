<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Interop\Container\ContainerInterface as ContainerInterface;

class Comment_Service {
  protected $container;
  protected $db;

  // constructor receives container instance
  public function __construct(ContainerInterface $container) {
    $this->container = $container;
    $this->db = $container->get('db');
  }

  public function create (Request $request, Response $response) {
    // Parse body.
    $comment = $request->getParsedBody();

    // Validate input.
    if (!$comment['blog_post_id'] || !$comment['body']) {
      $response = $response->withStatus(422);
      return $response;
    }

    // Input.
    $id = uniqid();
    $created = time();
    $updated = $created;
    $account_id = $request->getHeader('account-id')[0];
    $blog_post_id = $comment['blog_post_id'];
    $body = $comment['body'];

    // SQL.
    $sql = "
    INSERT INTO comments
      (id,
      created,
      updated,
      account_id,
      blog_post_id,
      body)
    VALUES
      ('$id',
      '$created',
      '$updated',
      '$account_id',
      '$blog_post_id',
      :body)
    ";

    // Try to execute sql.
    $statement = NULL;
    try {
      $statement = $this->db->prepare($sql);
      $statement->bindParam(':body', $body);
      $statement->execute();
    } catch (PDOException $e) {
      throw($e);
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
    $response = $response->withStatus(501);
    return $response;
  }
  public function update (Request $request, Response $response) {

    // Check session
    if (!$request->getAttribute('session')) {
      $response = $response->withStatus(401);
      return $response;
    }

    // If not admin, Try sql query to check ownership of blog post.
    $targetId = $request->getAttribute('id');
    $statement = NULL;
    try {
      $sql = "
      SELECT * FROM comments
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

    // Get blog_post if found.
    $comment = $statement->fetch();

    // If blog_post is not owned and client is not admin.
    if ($request->getHeader('account-id')[0] != $comment['account_id']
        && $request->getAttribute('role') != 1) {
      $response = $response->withStatus(403);
      return $response;
    }

    // Parse update body.
    $update = $request->getParsedBody();

    $updated = time();
    $body = $update['body'];

    // Try Update SQL.
    $statement = NULL;
    try {
      $sql = "
      UPDATE comments
      SET updated = $updated,
      body = IF(:body = '', body, :body)
      WHERE id = '$targetId'
      ";
      $statement = $this->db->prepare($sql);
      $statement->bindParam(':body', $body);
      $statement->execute();
    } catch (PDOexception $e) {
      // If duplicate error occurs, return early.
      throw($e);
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

    // If not admin, Try sql query to check ownership of blog post.
    $targetId = $request->getAttribute('id');
    $statement = NULL;
    try {
      $sql = "
      SELECT * FROM comments
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

    // Get blog_post if found.
    $comment = $statement->fetch();

    // If blog_post not owned, and client is not admin.
    if ($request->getHeader('account-id')[0] != $comment['account_id']
        && $request->getAttribute('role') != 1) {
      $response = $response->withStatus(403);
      return $response;
    }

    // Try SQL.
    $statement = NULL;
    $targetId = $request->getAttribute('id');
    try {
      $sql = "
      DELETE FROM comments
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
  public function listBlogPost (Request $request, Response $response) {

    // Get username from param query.
    $account_id = $request->getAttribute('id');
    // Get list pagination, default to 0 if NULL.
    $page = $request->getQueryParam('page', $default = 0);
    // Get list size.
    $size = 5;
    // Default offset to null.
    $offset = ($size * $page);

    // Default statement to NULL.
    $statement = NULL;

    // Try SQL with query param, by like username, limited.
    if ($account_id) {
      try {
        $sql = "
        SELECT *
        FROM comments
        WHERE
          account_id = '$account_id'
        ORDER BY created
        LIMIT $offset, $size
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
        SELECT *
        FROM blog_posts
        WHERE
          privacy = 0
        ORDER BY created
        LIMIT $offset, $size
        ";
        $statement = $this->db->query($sql);
      } catch (PDOexception $e) {
        throw($e);
        // If internal database error, return early.
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
  public function listAccount (Request $request, Response $response) {
    $response = $response->withStatus(501);
    return $response;
  }
}

?>
