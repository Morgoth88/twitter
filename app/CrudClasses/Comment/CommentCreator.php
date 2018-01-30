<?php

namespace App\CrudClasses\Comment;

use App\Repositories\CommentDataRepository;

class CommentCreator
{

    private $commentDataRepository;


    public function __construct(CommentDataRepository $commentDataRepository)
    {
        $this->commentDataRepository = $commentDataRepository;
    }


    /**
     * create comment
     *
     * @param $request
     * @param $post
     * @return mixed
     */
    public function createPost($request, $post)
    {
        return $this->commentDataRepository->createComment($request, $post);
    }

}
