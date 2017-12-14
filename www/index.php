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
 * CORS
 */
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', 'http://mysite')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

/**
 * Session middleware
 */
$app->add(function ($request, $response, $next) {

  // Default session to false.
  $request = $request->withAttribute('session', false);

  // If session headers are present.
  if ($request->hasHeader('account-id') && $request->hasHeader('session-id')) {

    // Localize id's.
    $session_id = $request->getHeader('session-id');
    $account_id = $request->getHeader('account-id');

    // Try SQL.
    $statement = NULL;
    try {
      $sql = "SELECT accounts.role FROM sessions INNER JOIN accounts ON sessions.account_id = accounts.id WHERE id = '$session_id' AND account_id = '$account_id' LIMIT 1";
      $statement = $this->db->query($sql);
    } catch (PDOexception $e) {
      $response = $response->withStatus(500);
      return $response;
    }

    // If session not matched, continue with no change.
    if ($statement->rowCount() == 0) {
      next($request, $response);
    }

    // If matched, set session to true.
    else {
      $result = $statement->fetch();
      $request = $request->withAttribute('role', $result['role']);
      $request = $request->withAttribute('session', true);
      next($request, $response);
    }
  }

  // If session headers are not present, continue with no change.
  else
    next($request, $response);

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

$app->post('/api/comment', \Comment_Service::class . ':create');
$app->get('/api/comment/{id}', \Comment_Service::class . ':retrieve');
$app->patch('/api/comment/{id}', \Comment_Service::class . ':update');
$app->delete('/api/comment/{id}', \Comment_Service::class . ':delete');
$app->get('/api/comment', \Comment_Service::class . ':list');

$app->run();

?>
