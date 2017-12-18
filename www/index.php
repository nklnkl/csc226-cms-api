<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require '../vendor/autoload.php';

require_once '../api/router.php';
require '../api/account.php';
require '../api/blog-post.php';
require '../api/comment.php';
require '../api/session.php';

$app = new \Slim\App;

/**
 * DB
 */
$container = $app->getContainer();
$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO('mysql:host=localhost;dbname=lagman_blog', 'lagman', 'niko4850');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

/**
 * Session middleware
 */
$mw = function ($request, $response, $next) {
  $response = $response
    ->withHeader('Access-Control-Allow-Origin', '*')
    ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');

  // Default session to false.
  $request = $request->withAttribute('session', false);

  // If session headers are present.
  if ($request->hasHeader('account_id') && $request->hasHeader('session_id')) {

    // Localize id's.
    $session_id = $request->getHeader('session_id');
    $account_id = $request->getHeader('account_id');

    // Try SQL.
    $statement = NULL;
    try {
      $sql = "
      SELECT accounts.role, accounts.status
      FROM sessions
      INNER JOIN accounts
        ON sessions.account_id = accounts.id
      WHERE
        sessions.id = '$session_id[0]'
        AND
        sessions.account_id = '$account_id[0]'
      LIMIT 1
      ";
      $statement = $this->db->query($sql);
    } catch (PDOexception $e) {
      $response = $response->withStatus(500);
      return $response;
    }

    // If session matched
    if ($statement->rowCount() != 0) {
      $result = $statement->fetch();
      $request = $request->withAttribute('role', $result['role']);
      $request = $request->withAttribute('session', true);
      $request = $request->withAttribute('status', $result['status']);
    }
  }

  $response = $next($request, $response);
  return $response;
};
$app->add($mw);

/**
 * CORS
 */
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});


/**
 * ROUTES
 */
$app->post('/api/account', \Account_Service::class . ':create');
$app->get('/api/account/{id}', \Account_Service::class . ':retrieve');
$app->patch('/api/account/{id}', \Account_Service::class . ':update');
$app->delete('/api/account/{id}', \Account_Service::class . ':delete');
$app->get('/api/account', \Account_Service::class . ':list');

$app->post('/api/session', \Session_Service::class . ':create');
$app->get('/api/session/{id}', \Session_Service::class . ':retrieve');
$app->patch('/api/session/{id}', \Session_Service::class . ':update');
$app->delete('/api/session/{id}', \Session_Service::class . ':delete');
$app->get('/api/session', \Session_Service::class . ':list');

$app->post('/api/blog-post', \Blog_Post_Service::class . ':create');
$app->get('/api/blog-post/{id}', \Blog_Post_Service::class . ':retrieve');
$app->patch('/api/blog-post/{id}', \Blog_Post_Service::class . ':update');
$app->delete('/api/blog-post/{id}', \Blog_Post_Service::class . ':delete');
$app->get('/api/blog-post', \Blog_Post_Service::class . ':list');
$app->get('/api/blog-post/search/content', \Blog_Post_Service::class . ':search');

$app->post('/api/comment', \Comment_Service::class . ':create');
$app->get('/api/comment/{id}', \Comment_Service::class . ':retrieve');
$app->patch('/api/comment/{id}', \Comment_Service::class . ':update');
$app->delete('/api/comment/{id}', \Comment_Service::class . ':delete');
$app->get('/api/comment/blog-post/{id}', \Comment_Service::class . ':listBlogPost');
$app->get('/api/comment/account/{id}', \Comment_Service::class . ':listAccount');

$app->run();

?>
