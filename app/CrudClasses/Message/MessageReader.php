<?php

namespace App\CrudClasses\Message;

use App\Repositories\MessageDataRepository;
use App\Exceptions\DataErrorException;

class MessageReader
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
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @throws DataErrorException
     */
    public function allPosts()
    {
        $data = $this->messageDataRepository->getAllMessages();
        if ($data) {
            return $data;
        } else {
            throw new DataErrorException('DataRepository error');
        }
    }

}
