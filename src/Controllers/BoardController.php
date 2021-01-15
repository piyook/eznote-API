<?php

namespace Src\Controllers;

use Src\Utils\InputHandler;
use Src\Controllers\NoteController;
use Src\Models\Board;



class BoardController extends NoteController
{

    protected $boards;
    protected $userId;
    protected $errorHandler;

    function __construct($validUserId)
    {
        $this->boards = new Board($validUserId);
        NoteController::__construct($validUserId);
    }

    public function newBoard()
    {
     
        $post_vars = json_decode(file_get_contents("php://input"),true);
        $required_vars = array("title","body","colour");

        InputHandler::validateClientInput($post_vars, $required_vars);

        $this->boards->createBoard($post_vars);
        $data = $this->boards->fetchAllBoards();
        print_r($data);
        
    }

    public function getBoards($uri)
    {
         $data = $this->boards->fetchAllBoards();
        print_r($data);
    }

    public function editBoard($uri)
    {
        $put_vars = json_decode(file_get_contents("php://input"),true);
        $required_vars = array("title","body","colour");

        InputHandler::validateClientInput($put_vars, $required_vars);

        $boardId = $uri[2];
        
        $data = $this->boards->updateBoard($boardId, $put_vars);
        $data = $this->boards->fetchAllBoards();

        print_r($data);
    }

    public function delBoard($uri)
    {
        $boardId = $uri[2];
        $this->boards->deleteBoard($boardId);
        $data = $this->boards->fetchAllBoards();
        print_r($data);
    }
}
