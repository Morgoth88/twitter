<?php

namespace App\CrudClasses\Message;

use App\Repositories\MessageDataRepository;
use App\Exceptions\NoDataException;

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
     * @throws NoDataException
     */
    public function readPost()
    {
        $data = $this->messageDataRepository->getAllMessages();
        if ($data) {
            return $data;
        } else {
            throw new NoDataException('No data');
        }
    }

}
