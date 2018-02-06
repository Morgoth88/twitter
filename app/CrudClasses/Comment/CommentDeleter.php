<?php

namespace App\CrudClasses\Comment;

use App\Repositories\CommentDataRepository;
use App\Exceptions\DataErrorException;

class CommentDeleter
{

    private $commentDataRepository;


    public function __construct(CommentDataRepository $commentDataRepository)
    {
        $this->commentDataRepository = $commentDataRepository;
    }


    /**
     * delete comment
     *
     * @param $post
     * @return mixed
     * @throws DataErrorException
     */
    public function deletePost($post)
    {
        $data = $this->commentDataRepository->delete($post);
        if ($data) {
            return $data;
        } else {
            throw new DataErrorException('DataRepository error');
        }
    }
}