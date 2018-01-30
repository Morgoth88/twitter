<?php

namespace App\CrudClasses\Message;

use App\Repositories\MessageDataRepository;

class MessageDeleter
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
     * delete post and return id
     *
     * @param $post
     * @return mixed
     */
    public function deletePost($post)
    {
        return $this->messageDataRepository->deleteMessage($post);
    }
}