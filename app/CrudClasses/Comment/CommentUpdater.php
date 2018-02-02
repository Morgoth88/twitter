<?php

namespace App\CrudClasses\Comment;

use App\Repositories\CommentDataRepository;
use App\Exceptions\DataErrorException;

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
     * @throws DataErrorException
     */
    public function updatePost($request, $comment)
    {
        $data = $this->commentDataRepository->updateComment($request, $comment);
        if ($data) {
            return $data;
        } else {
            throw new DataErrorException('DataRepository error');
        }
    }

}