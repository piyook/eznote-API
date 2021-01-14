<?php

namespace Src\Models;
use Src\Utils\SQLDatabase;

class Board extends SQLDatabase
{
    protected $database;
    
    public function __construct()
    {
        $this->database = new SQLDatabase;
    }


    public function fetchAllBoards()
    {
        $sql = 'SELECT * FROM noticeboards';
        return $this->database->execute($sql, []);
    }

    
    public function createBoard($data){

        $sql =' INSERT INTO noticeboards(title, body, colour) 
                VALUES (:title,:body,:colour)';

        return $this->database->execute($sql, 
                        ['title' =>$data['title'], 
                        'body'=>$data['body'], 
                        'colour'=>$data['colour']]);

    }


    public function updateBoard($id, $data){

        $sql = '
                UPDATE noticeboards
                SET 
                title=:title,
                body=:body,
                colour=:colour
                WHERE id = :id
                ';

        return $this->database->execute($sql, 
             ['id' => $id, 
             'title' =>$data['title'], 
             'body'=>$data['body'], 
             'colour'=>$data['colour']]);

    }

    public function deleteBoard($id)
    {

        $sql = 'DELETE FROM noticeboards WHERE id = :id LIMIT 1';

        $this->database->execute($sql, ['id' => $id]);

    }

}



?>