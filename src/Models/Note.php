<?php

namespace Src\Models;
use Src\Utils\SQLDatabase;

class Note
{
    protected $database;
    protected $userId;
    
    public function __construct($validUserId)
    {
        $this->database = new SQLDatabase;
        $this->userId = $validUserId;
    }


    public function fetchNotes($uri)
    {

        $sql = 'SELECT * FROM notes WHERE boardId = :id AND userId = :userId';

        return $this->database->execute($sql, [
            'id' => $uri[2],
            'userId' => $this->userId
            ]);
    }

    public function fetchOneNote($uri)
    {

        $sql = '    SELECT * FROM notes 
                    WHERE boardId = :boardId AND id = :id AND userId = :userId';


        return $this->database->execute($sql, [
                    'boardId'=>$uri[2], 
                    'id' => $uri[3],
                    'userId' => $this->userId
                    ]);
            
    }

    public function createNote($data){

        $sql = '    INSERT INTO notes(title, boardId, body, colour, userId) 
                    VALUES (:title,:boardId, :body,:colour, :userId)';

        return $this->database->execute($sql, [
            'title' =>$data['title'], 
            'boardId' => $data['boardId'], 
            'body'=>$data['body'], 
            'colour'=>$data['colour'],
            'userId' => $this->userId
             ]);

    }

    public function updateNote($id, $data){

        $sql = '
                UPDATE notes
                SET 
                title=:title,
                body=:body,
                colour=:colour
                WHERE id = :id AND userId = :userId
                ';

        return $this->database->execute($sql, [
            'title' =>$data['title'], 
            'body'=>$data['body'], 
            'colour'=>$data['colour'],
            'id'=>$id,
            'userId' => $this->userId 
            ]);
    }

    public function deleteNote($id)
    {

        $sql = 'DELETE FROM notes WHERE id = :id AND userId = :userId LIMIT 1';

        $this->database->execute($sql, [
            'id' => $id,
            'userId' => $this->userId
            ]);

    }

}

    ?>