<?php

namespace Src\Models;
use Src\Utils\SQLDatabase;

class Board
{
    protected $database;
    protected $userId;
    
    public function __construct($validUserId)
    {
        $this->database = new SQLDatabase;
        $this->userId = $validUserId;
    }


    public function fetchAllBoards()
    {
        $sql = 'SELECT * FROM noticeboards WHERE userId = :userId';
        return $this->database->execute($sql, ['userId' => $this->userId]);
    }

    
    public function createBoard($data){

        $sql =' INSERT INTO noticeboards(title, body, colour, userId) 
                VALUES (:title,:body,:colour,:userId)';

        return $this->database->execute($sql, 
                        ['title' =>$data['title'], 
                        'body'=>$data['body'], 
                        'colour'=>$data['colour'],
                        'userId'=>$this->userId
                        ]);

    }


    public function updateBoard($id, $data){

        $sql = '
                UPDATE noticeboards
                SET 
                title=:title,
                body=:body,
                colour=:colour
                WHERE id = :id AND userId = :userId
                ';

        return $this->database->execute($sql, 
             ['id' => $id, 
             'title' =>$data['title'], 
             'body'=>$data['body'], 
             'colour'=>$data['colour'],
             'userId'=>$this->userId]);

    }

    public function deleteBoard($id)
    {

        $sql = 'DELETE FROM noticeboards WHERE id = :id AND userId = :userId LIMIT 1';

        $this->database->execute($sql, [
            'id' => $id,
            'userId'=>$this->userId]);

    }

}



?>