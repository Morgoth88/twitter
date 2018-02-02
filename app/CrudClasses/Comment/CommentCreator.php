<?php

namespace App\CrudClasses\Comment;

use App\Repositories\CommentDataRepository;
use App\Exceptions\DataErrorException;

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
     * @throws DataErrorException
     */
    public function createPost($request, $post)
    {
        $data = $this->commentDataRepository->createComment($request, $post);
        if ($data) {
            return $data;
        } else {
            throw new DataErrorException('DataRepository error');
        }
    }

}
