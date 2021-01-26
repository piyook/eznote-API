<?php

namespace Src\Router;

class Router {

    public $uriParts; 
    public $validRoute;
    private $requestMethod;
    private $uri;
     // define all valid endpoints - this will act as a simple router
    private $routes = [
        'Login' => [
            'method' => 'POST',
            'expression' => '/^\/api\/login\/?$/',
            'controller_method' => 'login'
        ],
        'Register' => [
            'method' => 'POST',
            'expression' => '/^\/api\/register\/?$/',
            'controller_method' => 'register'
        ],
        'Refresh' => [
            'method' => 'POST',
            'expression' => '/^\/api\/refresh\/?$/',
            'controller_method' => 'refresh'
        ],
        'Logout' => [
            'method' => 'POST',
            'expression' => '/^\/api\/logout\/?$/',
            'controller_method' => 'logout'
        ],
        'Show-All-Boards' => [
            'method' => 'GET',
            'expression' => '/^\/api\/?$/',
            'controller_method' => 'getBoards'
        ],
        'Show-A-Boards-Content' => [
            'method' => 'GET',
            'expression' => '/^\/api\/(\d+)\/?$/',
            'controller_method' => 'getAllNotes'
        ],
        'Show-A-Note-Content' => [
            'method' => 'GET',
            'expression' => '/^\/api\/(\d+)\/(\d+)\/?$/',
            'controller_method' => 'getOneNote'
        ],
    
        'Create-A-New-Board' => [
            'method' => 'POST',
            'expression' => '/^\/api\/?$/',
            'controller_method' => 'newBoard'
        ],
        'Create-A-New-Note' => [
            'method' => 'POST',
            'expression' => '/^\/api\/(\d+)\/?$/',
            'controller_method' => 'newNote'
        ],
        
        'Edit-A-Board' => [
            'method' => 'PUT',
            'expression' => '/^\/api\/(\d+)\/?$/',
            'controller_method' => 'editBoard'
        ],
        'Edit-A-Note' => [
            'method' => 'PUT',
            'expression' => '/^\/api\/(\d+)\/(\d+)\/?$/',
            'controller_method' => 'editNote'
        ],
    
        'Delete-A-Board' => [
            'method' => 'DELETE',
            'expression' => '/^\/api\/(\d+)\/?$/',
            'controller_method' => 'delBoard'
        ],
        'Delete-A-Note' => [
            'method' => 'DELETE',
            'expression' => '/^\/api\/(\d+)\/(\d+)\/?$/',
            'controller_method' => 'delNote'
        ],
    ];
   
    function __construct() {

        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->uri = str_replace(ROOT_URL, "", $this->uri);
        
        $this->requestMethod = $_SERVER["REQUEST_METHOD"];
        $this->uriParts = explode( '/', $this->uri );

        foreach ($this->routes as $route) {
            if ($this->isRequestMethod($route['method']) &&
               $this->isRequestRoute($route['expression']))
            {
                $this->validRoute = $route;
                break;
            }
        }


        if (! $this->validRoute) {
            header("HTTP/1.1 404 Not Found");
            exit();
        }
  
    }

    private function isRequestMethod($method){
        return $method == $this->requestMethod;
    }

    private function isRequestRoute($route){
        return  preg_match($route, $this->uri);
    }

}
