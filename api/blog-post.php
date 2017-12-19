<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Interop\Container\ContainerInterface as ContainerInterface;

class Blog_Post_Service {
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
    if (!isset($blog_post['title']) || !isset($blog_post['body']) || !isset($blog_post['privacy'])) {
      $response = $response->withStatus(422);
      return $response;
    }

    // Prepare input.
    $id = uniqid();
    $created = time();
    $updated = $created;
    $account_id = $request->getHeader('account_id')[0];
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
      :title,
      :body,
      '$privacy')
    ";

    // Try to execute sql.
    $statement = NULL;
    try {
      $statement = $this->db->prepare($sql);
      $statement->bindParam(':title', $title);
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
    $response = $response->withStatus(200);
    $response = $response->withJson({ id: $id });
    return $response;
  }

  public function retrieve (Request $request, Response $response) {

    $targetId = $request->getAttribute('id');

    // Try SQL.
    $statement = NULL;
    try {
      $sql = "
      SELECT * FROM blog_posts
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
    $blog_post = $statement->fetch();

    // If blog_post is private, not owned, client is not admin.
    if ($blog_post['privacy'] == 1
        && $request->getHeader('account_id')[0] != $blog_post['account_id']
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
    $targetId = $request->getAttribute('id');
    $statement = NULL;
    try {
      $sql = "
      SELECT * FROM blog_posts
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
    $blog_post = $statement->fetch();

    // If blog_post is not owned and client is not admin.
    if ($request->getHeader('account_id')[0] != $blog_post['account_id']
        && $request->getAttribute('role') != 1) {
      $response = $response->withStatus(403);
      return $response;
    }

    // Parse update body.
    $update = $request->getParsedBody();

    $updated = time();
    $title = $update['title'];
    $body = $update['body'];
    $privacy = $update['privacy'];

    // Try Update SQL.
    $statement = NULL;
    try {
      $sql = "
      UPDATE blog_posts
      SET updated = $updated,
      title = IF(:title = '', title, :title),
      body = IF(:body = '', body, :body),
      privacy = IF('$privacy' = '', privacy, '$privacy')
      WHERE id = '$targetId'
      ";
      $statement = $this->db->prepare($sql);
      $statement->bindParam(':title', $title);
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

    // Success!
    $response = $response->withStatus(204);
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
      SELECT * FROM blog_posts
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
    $blog_post = $statement->fetch();

    // If blog_post not owned, and client is not admin.
    if ($request->getHeader('account_id')[0] != $blog_post['account_id']
        && $request->getAttribute('role') != 1) {
      $response = $response->withStatus(403);
      return $response;
    }

    // Try SQL.
    $statement = NULL;
    $targetId = $request->getAttribute('id');
    try {
      $sql = "
      DELETE FROM blog_posts
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
    $response = $response->withStatus(204);
    return $response;
  }
  public function list (Request $request, Response $response) {

    // Get username from param query.
    $account_id = $request->getQueryParam('account_id', $default = NULL);
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
        FROM blog_posts
        WHERE
          account_id = '$account_id'
          AND
          privacy = 0
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

  public function search (Request $request, Response $response) {

    // Get username from param query.
    $title = $request->getQueryParam('title', $default = NULL);
    // Get list pagination, default to 0 if NULL.
    $page = $request->getQueryParam('page', $default = 0);
    // Get list size.
    $size = 5;
    // Default offset to null.
    $offset = ($size * $page);

    // Default statement to NULL.
    $statement = NULL;

    // Try SQL with query param, by like username, limited.
    if ($title) {
      try {
        $sql = "
        SELECT *
        FROM blog_posts
        WHERE
          title LIKE '$title%'
          AND
          privacy = 0
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
}

?>
