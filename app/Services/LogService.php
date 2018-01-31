<?php

namespace App\Services;

use App\Repositories\LogDataRepository;

class LogService
{

    private $LogDataRepository;


    /**
     * LogService constructor.
     * @param LogDataRepository $dataRepository
     */
    public function __construct(LogDataRepository $dataRepository)
    {
        $this->LogDataRepository = $dataRepository;
    }


    /**
     * log record to Db
     *
     * @param $user
     * @param $message
     */
    public function log($user, $message)
    {
        $this->LogDataRepository->saveLog($user, $message);
    }

}
