<?php

require __DIR__."/../bootstrap.php";
use \Firebase\JWT\JWT;


use Src\Router\Router;
use Src\Controllers\BoardController;
use Src\Controllers\AuthController;

// send some CORS headers so the API can be called from anywhere
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Expose-Headers: Set-Cookie");
header("Access-Control-Allow-Credentials: true");
// header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Headers: Content-Type, Origin, Accept, Pragma, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$router = new Router;
$auth = new AuthController;

$methodName = $router->validRoute['controller_method'];

if(isAuthSetup($methodName)) {
    $auth->$methodName($router->uriParts);
} else {

    $validUserId = $auth->authenticate();
 
    if ( !$validUserId ) {
    header("HTTP/1.1 401 Unauthorized");
    exit('Unauthorized');
    }

    $board = new BoardController($validUserId);
    $board->$methodName($router->uriParts);
}

function isAuthSetup($methodName){
  $auth_path = ['login', 'register', 'refresh', 'logout']; 
  return in_array($methodName, $auth_path);
}



?>