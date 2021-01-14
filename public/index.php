<?php

require __DIR__."/../bootstrap.php";
use \Firebase\JWT\JWT;


use Src\Router\Router;
use Src\Controllers\BoardController;
use Src\Controllers\AuthController;

// send some CORS headers so the API can be called from anywhere
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$router = new Router;
$auth = new AuthController;
$board = new BoardController;

$methodName = $router->validRoute['controller_method'];

if($methodName === 'login' || $methodName === 'register' || $methodName=="refresh") {
    $auth->$methodName($router->uriParts);
} else {
 
    if (! $auth->authenticate()) {
    header("HTTP/1.1 401 Unauthorized");
    exit('Unauthorized');
    }

    $board->$methodName($router->uriParts);
}



?>