<?php

namespace App\CrudClasses\Comment;

use App\Repositories\CommentDataRepository;
use App\Exceptions\DataErrorException;

class CommentReader
{

    private $commentDataRepository;


    public function __construct(CommentDataRepository $commentDataRepository)
    {
        $this->commentDataRepository = $commentDataRepository;
    }


    /**
     * read all comments from repo
     *
     * @param $post
     * @return mixed
     * @throws DataErrorException
     */
    public function readPost($post)
    {
        $data = $this->commentDataRepository->getAllComments($post);
        if ($data) {
            return $data;
        } else {
            throw new DataErrorException('DataRepository error');
        }
    }
}