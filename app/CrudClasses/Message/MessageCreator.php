<?php

namespace App\CrudClasses\Message;

use App\Repositories\MessageDataRepository;
use App\Exceptions\DataErrorException;
use App\Services\UrlSearcherService;

class MessageCreator
{

    private $messageDataRepository;

    private  $urlSearcher;


    /**
     * MessageCreator constructor.
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
     *  create message and if text contains urls change urls to links
     *
     * @param $request
     * @return null
     * @throws DataErrorException
     */
    public function createPost($request)
    {
        $request->tweet = $this->urlSearcher->UrlToLink($request->tweet);

        $data = $this->messageDataRepository->createMessage($request);
        if ($data) {
            return $data;
        } else {
            throw new DataErrorException('DataRepository error');
        }
    }

}