<?php

namespace App\CrudClasses\Comment;

use App\Repositories\CommentDataRepository;
use App\Exceptions\DataErrorException;
use App\Services\UrlSearcherService;

class CommentCreator
{

    private $commentDataRepository;

    private $urlSearcher;


    /**
     * CommentCreator constructor.
     * @param CommentDataRepository $commentDataRepository
     * @param UrlSearcherService $urlSearcherService
     */
    public function __construct(CommentDataRepository $commentDataRepository,
                                UrlSearcherService $urlSearcherService)
    {
        $this->commentDataRepository = $commentDataRepository;
        $this->urlSearcher = $urlSearcherService;
    }


    /**
     * create comment and if text contains urls change urls to links
     *
     * @param $request
     * @param $post
     * @return mixed
     * @throws DataErrorException
     */
    public function createPost($request, $post)
    {
        $request->comment = $this->urlSearcher->UrlToLink($request->comment);

        $data = $this->commentDataRepository->createComment($request, $post);
        if ($data) {
            return $data;
        } else {
            throw new DataErrorException('DataRepository error');
        }
    }

}
