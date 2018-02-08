<?php

namespace App\Services;

use App\Repositories\CommentDataRepository;
use App\Repositories\MessageDataRepository;

class DbCleanService
{


    private $messageDataRepository;

    private $commentDataRepository;

    private $timeHelper;


    /**
     * DbCleanService constructor.
     * @param TimeHelperService $timeHelper
     * @param MessageDataRepository $messageRepo
     * @param CommentDataRepository $commentRepo
     */
    public function __construct(TimeHelperService $timeHelper,
                                MessageDataRepository $messageRepo,
                                CommentDataRepository $commentRepo)
    {
        $this->timeHelper = $timeHelper;
        $this->commentDataRepository = $commentRepo;
        $this->messageDataRepository = $messageRepo;
    }


    /**
     * check if oldest records is older than 1 week
     * and clean old messages and comments from DB
     *
     */
    public function periodicOldRecordsClean()
    {
        $oldestMessageDate = $this->messageDataRepository->getOldestRecord();
        $oldestCommentDate = $this->commentDataRepository->getOldestRecord();

        if ($this->timeHelper->weekPassed($oldestMessageDate)) {

            $oldMessages = $this->messageDataRepository->getOldRecords();

            $this->messageDataRepository->deleteAll($oldMessages);

        }

        if ($this->timeHelper->weekPassed($oldestCommentDate)) {

            $oldComments = $this->messageDataRepository->getOldRecords();

            $this->commentDataRepository->deleteAll($oldComments);
        }
    }

}
