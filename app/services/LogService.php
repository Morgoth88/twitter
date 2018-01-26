<?php

namespace App\services;

use App\ActivityLog;

class LogService
{

    /**
     * @var ActivityLog
     */
    private $activityLog;


    /**
     * LogService constructor.
     * @param ActivityLog $activityLog
     */
    public function __construct(ActivityLog $activityLog)
    {
        $this->activityLog = $activityLog;
    }


    /**
     * log record to Db
     *
     * @param $user
     * @param $message
     */
    public function log($user, $message)
    {
        $this->activityLog->user_id = $user->id;
        $this->activityLog->activity = $message;
        $this->activityLog->save();
    }

}
