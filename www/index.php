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

$app->post('/api/account', '\Account_Service:create');
$app->get('/api/account/{id}', '\Account_Service:retrieve');
$app->patch('/api/account/{id}', '\Account_Service:update');
$app->delete('/api/account/{id}', '\Account_Service:delete');
$app->get('/api/account', '\Account_Service:list');

$app->post('/api/session', '\Session_Service:create');
$app->get('/api/session/{id}', '\Session_Service:retrieve');
$app->patch('/api/session/{id}', '\Session_Service:update');
$app->delete('/api/session/{id}', '\Session_Service:delete');
$app->get('/api/session', '\Session_Service:list');

$app->post('/api/blog-post', '\Blog_Post_Service:create');
$app->get('/api/blog-post/{id}', '\Blog_Post_Service:retrieve');
$app->patch('/api/blog-post/{id}', '\Blog_Post_Service:update');
$app->delete('/api/blog-post/{id}', '\Blog_Post_Service:delete');
$app->get('/api/blog-post', '\Blog_Post_Service:list');

$app->post('/api/comment', '\Comment_Service:create');
$app->get('/api/comment/{id}', '\Comment_Service:retrieve');
$app->patch('/api/comment/{id}', '\Comment_Service:update');
$app->delete('/api/comment/{id}', '\Comment_Service:delete');
$app->get('/api/comment', '\Comment_Service:list');

$app->run();

?>
