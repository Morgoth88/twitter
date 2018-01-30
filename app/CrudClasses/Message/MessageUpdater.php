<?php

namespace App\CrudClasses\Message;

use App\Repositories\MessageDataRepository;

class MessageUpdater
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
     * update message
     *
     * @param $request
     * @param $post
     * @return mixed
     */
    public function updatePost($request, $post)
    {
        return $this->messageDataRepository->updateMessage($request, $post);
    }

}
