<?php

namespace Src\Controllers;

use Src\Controllers\NoteController;
use Src\Models\Board;


class BoardController extends NoteController
{

    protected $boards;

    function __construct()
    {
        $this->boards = new Board;
        NoteController::__construct();
    }

    public function newBoard()
    {
        parse_str(file_get_contents("php://input"), $post_vars);
        $post_vars = json_decode(file_get_contents("php://input"),true);

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
        parse_str(file_get_contents("php://input"), $put_vars);
        $put_vars = json_decode(file_get_contents("php://input"),true);

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
