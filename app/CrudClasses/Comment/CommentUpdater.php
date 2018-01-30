<?php

namespace App\CrudClasses\Comment;

use App\Repositories\CommentDataRepository;

class CommentUpdater
{


    private $commentDataRepository;


    public function __construct(CommentDataRepository $commentDataRepository)
    {
        $this->commentDataRepository = $commentDataRepository;
    }


    /**
     * update comment
     *
     * @param $request
     * @param $comment
     * @return mixed
     */
    public function updatePost($request, $comment)
    {
        return $this->commentDataRepository->updateComment($request, $comment);
    }

}