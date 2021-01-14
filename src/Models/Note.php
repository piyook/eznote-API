<?php

namespace Src\Models;
use Src\Utils\Model;

class Note extends Model
{
    protected $database;
    
    public function __construct()
    {
        $this->database = new Model;
    }


    public function fetchNotes($uri)
    {

        $sql = 'SELECT * FROM notes WHERE boardId = :id';

        return $this->database->execute($sql, ['id' => $uri[2]]);
    }

    public function fetchOneNote($uri)
    {

        $sql = '    SELECT * FROM notes 
                    WHERE boardId = :boardId AND id = :id';


        return $this->database->execute($sql, [
                    'boardId'=>$uri[2], 
                    'id' => $uri[3]
                    ]);
            
    }

    public function createNote($data){

        $sql = '    INSERT INTO notes(title, boardId, body, colour) 
                    VALUES (:title,:boardId, :body,:colour)';

        return $this->database->execute($sql, [
            'title' =>$data['title'], 
            'boardId' => $data['boardId'], 
            'body'=>$data['body'], 
            'colour'=>$data['colour'] ]);

    }

    public function updateNote($id, $data){

        $sql = '
                UPDATE notes
                SET 
                title=:title,
                body=:body,
                colour=:colour
                WHERE id = :id
                ';

        return $this->database->execute($sql, [
            'title' =>$data['title'], 
            'body'=>$data['body'], 
            'colour'=>$data['colour'],
            'id'=>$id 
            ]);
    }

    public function deleteNote($id)
    {

        $sql = 'DELETE FROM notes WHERE id = :id LIMIT 1';

        $this->database->execute($sql, ['id' => $id]);

    }

}

    ?>