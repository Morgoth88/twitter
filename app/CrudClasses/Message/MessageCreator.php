<?php

namespace App\CrudClasses\Message;

use App\Repositories\MessageDataRepository;

class MessageCreator
{

    private $messageDataRepository;


    /**
     * MessageReader constructor.
     * @param MessageDataRepository $messageDataRepository
     */
    public function __construct(MessageDataRepository $messageDataRepository)
    {
        $this->messageDataRepository = $messageDataRepository;
    }


    /**
     * create message
     *
     * @param $request
     * @return mixed
     */
    public function createPost($request)
    {
        return $this->messageDataRepository->createMessage($request);
    }

}
