<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require '../vendor/autoload.php';
require 'account.php';

$app = new \Slim\App;
$app->get('/', '\AccountService:create');
$app->run();

?>
