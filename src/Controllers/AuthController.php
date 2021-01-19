<?php

namespace Src\Controllers;

use Src\Models\Auth;
use \Firebase\JWT\JWT;
use Src\Utils\InputHandler;

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

       
        InputHandler::validateAuthInput($post_vars);

        $this->auth->loginUser($post_vars);
        
    }

    public function register()
    {
    
        parse_str(file_get_contents("php://input"), $post_vars);
        $post_vars = json_decode(file_get_contents("php://input"), true);

        InputHandler::validateAuthInput($post_vars);

        $post_vars['password'] = password_hash($post_vars['password'], PASSWORD_DEFAULT);

        $this->auth->registerUser($post_vars);
        
    }

    public function refresh(){
       
        $validUserId = $this->validateCookie('refreshToken');

        if(!$validUserId) { return false;}

        $isBlacklisted = $this->auth->isTokenBlacklisted( $validUserId );

        if ($isBlacklisted) {
            http_response_code(401);
            echo json_encode(array(
                "message" => "Access DENIED:",
            ));
            die(); 
        }
        else {
          
            $userData = JWT::decode($_COOKIE['refreshToken'], SECRET_KEY, array('HS256'));
          
            $this->auth->refreshAccessToken( $userData->data);

    }
}

    public function authenticate(){

       

        if (!isset($_COOKIE['accessToken']) ) { 
                        
            return false;}
        $secret_key=SECRET_KEY;
        $jwt = null;
        $jwt = $_COOKIE['accessToken'];
    
        try {
    
            $userData = JWT::decode($jwt, $secret_key, array('HS256'));
            $userId = $userData->data->id;
            return $userId;
    
        } catch (\exception $e){
            http_response_code(401);
            echo json_encode(array(
                "message" => "Access DENIED:",
                "error" => $e->getMessage()
            ));
            die();
    
        }      
}

function validateCookie($cookieName){

    if (!isset($_COOKIE[$cookieName]) ) { return false;}

    $secret_key=SECRET_KEY;
    $jwt = null;
    $jwt = $_COOKIE[$cookieName];

    try {

        $userData = JWT::decode($jwt, $secret_key, array('HS256'));
        $userId = $userData->data->id;
        return $userId;

    } catch (\exception $e){
        http_response_code(401);
        echo json_encode(array(
            "message" => "Access DENIED:",
            "error" => $e->getMessage()
        ));
        die();

    }      
}






}


