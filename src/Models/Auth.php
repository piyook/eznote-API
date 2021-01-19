<?php

namespace Src\Models;

use Src\Utils\SQLDatabase;
use Src\Utils\TokenHandler;

class Auth
{

    protected $database;


    public function __construct()
    {
        $this->database = new SQLDatabase;
    }


    public function registerUser($data)
    {
        if ($this->isEmailTaken($data['email'])) {
            return;
        }

        $this->addToDatabase($data);
    }


    public function loginUser($data){

        $results = $this->getUserData($data);

        $isUserValid = $this->validateLogin($data['password'], $results);

        if  ($isUserValid) {

            $token = TokenHandler::generateAccessToken($results);

            TokenHandler::generateRefeshToken($results);

            print_r($token);

        } else {
            http_response_code(401);
                echo json_encode(array(
                    "message" => "LOGIN FAILED: Valid Email and Password Required:",
                ));
                die();
        }
    }

    public function refreshAccessToken($data){

        $token = TokenHandler::generateAccessToken(array($data));

       echo $token;
    }

    private function isEmailTaken($email)
    {

        $sql = "SELECT COUNT(*) AS `emailCount` FROM users WHERE email LIKE :email";

        $results = json_decode(
            $this->database->execute($sql, ['email' => $email])
        );

        return $results[0]->emailCount;
    }

    private function addToDatabase($data)
    {
        $sql = ' INSERT INTO users(email, password) 
        VALUES (:email,:password)';

        return $this->database->execute(
            $sql,
            [
                'email' => $data['email'],
                'password' => $data['password'],
            ]
        );
    }

    private function getUserData($data){

        $sql = "SELECT `id`, `email`, `password` FROM users WHERE email = :email LIMIT 1";

       return json_decode(
            $this->database->execute($sql, ['email' => $data['email']])
        );

    }

    private function validateLogin($userPassword, $results){
        
        if (count($results) === 0) {
            return false;}

        $encryptedPassword = $results[0]->password;
        return password_verify($userPassword, $encryptedPassword);
    }

    public function isTokenBlacklisted($id){
        
        return json_decode(TokenHandler::checkBlacklist($this->database));
    }




    

  
}
