<?php

namespace Src\Utils;
use PDO;

class SQLDatabase {

    private $pdo;

    public function __construct()
    {

        $host=HOST;
        $user=USER;
        $password=PASSWORD;
        $dbname=DBNAME;

        $dsn = 'mysql:host=' . $host . ';dbname=' . $dbname;

        try {

            $this->pdo = new PDO($dsn, $user, $password, array(
                PDO::ATTR_PERSISTENT => true));

            //set preferences in the attributes - E.g default fetch mode to get object srather than an array
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (\PDOException $e) {

            echo $e->getMessage(); // for testing only remove in production

            echo ("<h1><center>Sorry - Unable To Connect To The Server At This Time. <br>Please Try Again Later</center></h1>");
            die();
        }
    }


    public function execute($sql, $str){

        $stmt = $this->pdo->prepare($sql);

        try {
            $stmt->execute($str);
            return json_encode($stmt->fetchAll());
            }

        catch(\Exception $e) {

            return FALSE;
}
    }

}