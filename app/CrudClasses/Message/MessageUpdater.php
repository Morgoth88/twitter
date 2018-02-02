<?php

namespace App\CrudClasses\Message;

use App\Repositories\MessageDataRepository;
use App\Exceptions\DataErrorException;

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
     * @throws DataErrorException
     */
    public function updatePost($request, $post)
    {
        $data = $this->messageDataRepository->updateMessage($request, $post);
        if ($data) {
            return $data;
        } else {
            throw new DataErrorException('DataRepository error');
        }
    }

}
