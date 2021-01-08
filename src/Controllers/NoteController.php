<?php 

namespace Src\Controllers;

use Src\Models\Note;

class NoteController {

    protected $notes;

    function __construct()
    {
        $this->notes = new Note;
    }

    public function newNote($uri){

        parse_str(file_get_contents("php://input"), $post_vars);
        $post_vars = json_decode(file_get_contents("php://input"),true);

        $this->notes->createNote($post_vars);
        $data = $this->notes->fetchNotes($uri);
        print_r($data);
    }
    
    public function getAllNotes($uri)
    {
        $data = $this->notes->fetchNotes($uri);
        print_r($data);

    }


    public function getOneNote($uri){
        $data = $this->notes->fetchOneNote($uri);
        print_r($data);
    }

    public function editNote($uri){

        parse_str(file_get_contents("php://input"), $put_vars);
        $put_vars = json_decode(file_get_contents("php://input"),true);

        $noteId = $uri[3];

        $this->notes->updateNote($noteId, $put_vars);
        
        $data = $this->notes->fetchNotes($uri);
        print_r($data);
    }

    public function delNote($uri){

        $noteId = $uri[3];
        $this->notes->deleteNote($noteId);

        $data = $this->notes->fetchNotes($uri);
        print_r($data);
    }
}
