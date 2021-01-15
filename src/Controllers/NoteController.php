<?php 

namespace Src\Controllers;

use Src\Models\Note;
use Src\Utils\InputHandler;

class NoteController {

    protected $notes;

    function __construct($validUserId)
    {
        $this->notes = new Note($validUserId);
    }

    public function newNote($uri){

        $post_vars = json_decode(file_get_contents("php://input"),true);

        $required_vars = array("title","body","colour","boardId");

        InputHandler::validateClientInput($post_vars, $required_vars);

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

    
        $put_vars = json_decode(file_get_contents("php://input"),true);
        
        $required_vars = array("title","body","colour","boardId");
        InputHandler::validateClientInput($put_vars, $required_vars);

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
