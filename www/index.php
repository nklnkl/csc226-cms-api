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
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
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
