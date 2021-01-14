<?php

require __DIR__."/../bootstrap.php";


use Src\Router\Router;
use Src\Controllers\BoardController;

// send some CORS headers so the API can be called from anywhere
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$router = new Router;
$board = new BoardController;

$methodName = $router->validRoute['controller_method'];

$board->$methodName($router->uriParts);

?>