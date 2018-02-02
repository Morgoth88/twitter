<?php

namespace App\CrudClasses\Message;

use App\Repositories\MessageDataRepository;
use App\Exceptions\DataErrorException;

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
     *  create message
     *
     * @param $request
     * @return null
     * @throws DataErrorException
     */
    public function createPost($request)
    {
        $data = $this->messageDataRepository->createMessage($request);
        if ($data) {
            return $data;
        } else {
            throw new DataErrorException('DataRepository error');
        }
    }

}
