<?php

namespace App\services;

use App\Comment;
use App\Message;
use App\Repositories\CommentDataRepository;
use App\Repositories\LogDataRepository;
use App\Repositories\MessageDataRepository;

class DbCleanService
{

    private $LogRepo;


    private $MessageRepo;


    private $CommentRepo;


    private $timeHelper;


    private $foreacher;


    /**
     * DbCleanService constructor.
     * @param LogDataRepository $logDataRepository
     * @param TimeHelperService $timeHelper
     * @param MessageDataRepository $messageRepo
     * @param CommentDataRepository $commentRepo
     * @param ForeacherService $foreacherService
     */
    public function __construct(LogDataRepository $logDataRepository,
                                TimeHelperService $timeHelper,
                                MessageDataRepository $messageRepo,
                                CommentDataRepository $commentRepo,
                                ForeacherService $foreacherService)
    {
        $this->LogRepo = $logDataRepository;
        $this->timeHelper = $timeHelper;
        $this->CommentRepo = $commentRepo;
        $this->MessageRepo = $messageRepo;
        $this->foreacher = $foreacherService;
    }


    /**
     * check if oldest record in log table is older than 1 week and delete
     * all newest logs
     *
     */
    public function PeriodicLogClean()
    {
        $oldestLogTime = $this->LogRepo->getOldestRecord();

        if ($this->timeHelper->weekPassed($oldestLogTime)) {
            $this->LogRepo->deleteOldRecords($oldestLogTime);
        }
    }


    /**
     * check if oldest records is older than 1 week
     * and clean old messages and comments from DB
     *
     */
    public function periodicOldRecordsClean()
    {
        $oldestMessageTime = $this->MessageRepo->getOldestRecord(Message::class);
        $oldestCommentTime = $this->CommentRepo->getOldestRecord(Comment::class);

        if ($this->timeHelper->weekPassed($oldestMessageTime)) {
            $this->foreacher->OrmDeleteForeach(
                $this->MessageRepo->getOldRecords(Message::class)
            );
        }

        if ($this->timeHelper->weekPassed($oldestCommentTime)) {
            $this->foreacher->OrmDeleteForeach(
                $this->CommentRepo->getOldRecords(Comment::class)
            );
        }
    }

}
