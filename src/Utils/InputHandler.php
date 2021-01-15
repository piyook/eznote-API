<?php

namespace Src\Utils;

class InputHandler
{


    public static function validateClientInput($input, $required_vars)
    {

        foreach ($required_vars as $var) {

            if (!isset($input[$var])) {

                http_response_code(401);
                echo json_encode(array(
                    "ERROR" => "ACCESS DENIED: Valid Parameters Required:",
                ));
                die();
            }
        }
    }

    public static function validateAuthInput($input)
    {

        if (!isset($input['password']) || !isset($input['email'])) {

            http_response_code(401);
            echo json_encode(array(
                "ERROR" => "ACCESS DENIED: Valid Credentials Required:",
            ));
            die();
        }
    }
}
