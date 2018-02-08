<?php

namespace App\CrudClasses\Message;

use App\Repositories\MessageDataRepository;
use App\Exceptions\DataErrorException;
use App\Services\UrlSearcherService;

class MessageUpdater
{

    private $messageDataRepository;

    private  $urlSearcher;

    /**
     * MessageReader constructor.
     * @param MessageDataRepository $messageDataRepository
     * @param UrlSearcherService $urlSearcherService
     */
    public function __construct(MessageDataRepository $messageDataRepository,
                                UrlSearcherService $urlSearcherService)
    {
        $this->messageDataRepository = $messageDataRepository;
        $this->urlSearcher = $urlSearcherService;
    }


    /**
     * update message and if text contains urls change urls to links
     *
     * @param $request
     * @param $post
     * @return mixed
     * @throws DataErrorException
     */
    public function updatePost($request, $post)
    {
        $request->tweet = $this->urlSearcher->UrlToLink($request->tweet);

        $data = $this->messageDataRepository->updateMessage($request, $post);
        if ($data) {
            return $data;
        } else {
            throw new DataErrorException('DataRepository error');
        }
    }

}
