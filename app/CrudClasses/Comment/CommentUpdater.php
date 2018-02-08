<?php

namespace App\CrudClasses\Comment;

use App\Repositories\CommentDataRepository;
use App\Exceptions\DataErrorException;
use App\Services\UrlSearcherService;

class CommentUpdater
{

    private $commentDataRepository;

    private $urlSearcher;


    /**
     * CommentUpdater constructor.
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
     * update comment and if text contains urls change urls to links
     *
     * @param $request
     * @param $comment
     * @return mixed
     * @throws DataErrorException
     */
    public function updatePost($request, $comment)
    {
        $request->comment = $this->urlSearcher->UrlToLink($request->comment);

        $data = $this->commentDataRepository->updateComment($request, $comment);
        if ($data) {
            return $data;
        } else {
            throw new DataErrorException('DataRepository error');
        }
    }

}