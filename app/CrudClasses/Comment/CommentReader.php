<?php

namespace App\CrudClasses\Comment;

use App\Repositories\CommentDataRepository;

class CommentReader
{

    private $commentDataRepository;


    public function __construct(CommentDataRepository $commentDataRepository)
    {
        $this->commentDataRepository = $commentDataRepository;
    }


    public function readPost($post)
    {
        return $this->commentDataRepository->getAllComments($post);
    }
}