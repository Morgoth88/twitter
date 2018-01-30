<?php

namespace App\CrudClasses\Message;

use App\Repositories\MessageDataRepository;

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
     */
    public function readPost()
    {
        return $this->messageDataRepository->getAllMessages();
    }

}
