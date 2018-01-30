<?php

namespace App\CrudClasses\Comment;

use App\Repositories\CommentDataRepository;

class CommentDeleter
{

    private $commentDataRepository;


    public function __construct(CommentDataRepository $commentDataRepository)
    {
        $this->commentDataRepository = $commentDataRepository;
    }


    public function deletePost($post)
    {
        return $this->commentDataRepository->deleteComment($post);
    }
}