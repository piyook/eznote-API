<?php

namespace Src\Controllers;

use Src\Models\Auth;
use \Firebase\JWT\JWT;

class AuthController
{

    public function __construct()
    {

        $this->auth = new Auth;
    }


    public function login()
    {
        parse_str(file_get_contents("php://input"), $post_vars);
        $post_vars = json_decode(file_get_contents("php://input"), true);

        $this->auth->loginUser($post_vars);
    }

    public function register()
    {

        parse_str(file_get_contents("php://input"), $post_vars);
        $post_vars = json_decode(file_get_contents("php://input"), true);

        $post_vars['password'] = password_hash($post_vars['password'], PASSWORD_DEFAULT);

        $this->auth->registerUser($post_vars);
    }

    public function authenticate(){

        $secret_key=SECRET_KEY;
        $jwt = null;
    
        // extract the token from the headers
        if (! isset($_SERVER['HTTP_AUTHORIZATION'])) {
            return false;
        }
    
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
    
        //check bearer token header is sent
        preg_match('/Bearer\s(\S+)/', $authHeader, $matches);
    
        if(! isset($matches[1])) {
            return false;
        }
    
        $arr = explode(" ", $authHeader);
    
    
        $jwt  = $arr[1];
    
        try {
    
            JWT::decode($jwt, $secret_key, array('HS256'));
            return true;
    
        } catch (\exception $e){
            http_response_code(401);
            echo json_encode(array(
                "message" => "Access DENIED:",
                "error" => $e->getMessage()
            ));
            return false;
    
        }      
}

public function refresh(){
    echo "refresh";
}

}
