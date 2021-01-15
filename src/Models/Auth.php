<?php

namespace Src\Models;

use Src\Utils\SQLDatabase;
use \Firebase\JWT\JWT;

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

            echo("logged in");
            $token = $this->generateToken($results);
            print_r($token);

        } else {
            echo("failed login");
        }
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

    private function generateToken($data){

                $id = $data[0]->id;
                $email = $data[0]->email;


                $secret_key = SECRET_KEY;
                $issuer_claim = THE_ISSUER; // this can be the server name
                $audience_claim = THE_AUDIENCE;
                $issuedat_claim = time();
                $notbefore_claim = $issuedat_claim + 1; // not  before in sec
                $expire_claim = $issuedat_claim + 900; //expire time in sec
                $token = array(
                    'iss'=>$issuer_claim,
                    'aud' => $audience_claim,
                    'iat' => $issuedat_claim,
                    'nbf' => $notbefore_claim,
                    'exp' => $expire_claim,
                    "data" => array(
                        'id' => $id,
                        'email' => $email,
                    )
                    );

                $jwt = JWT::encode($token, $secret_key);

                echo json_encode( array (
                    "message" => "successful login",
                    "jwt" => $jwt,
                    "email" => $email,
                    "expireAt" => $expire_claim,
                )
                );

    }
   
}
