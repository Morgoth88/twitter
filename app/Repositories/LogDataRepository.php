<?php

namespace App\Repositories;

use App\ActivityLog;

class LogDataRepository
{

    private $activityLog;


    /**
     * LogDataRepository constructor.
     * @param ActivityLog $activityLog
     */
    public function __construct(ActivityLog $activityLog)
    {
        $this->activityLog = $activityLog;
    }


    /**
     * return oldest record in table
     *
     * @return mixed
     */
    public function getOldestRecord()
    {
        return ActivityLog::min('created_at');
    }


    /**
     * deletes all records that are newer than oldest record
     *
     * @param $oldestLogTime
     */
    public function deleteOldRecords($oldestLogTime)
    {
        ActivityLog::where('created_at', '>=', $oldestLogTime)
            ->delete();
    }


    /**
     * save log to Db
     *
     * @param $user
     * @param $message
     */
    public function saveLog($user, $message)
    {
        $this->activityLog->user_id = $user->id;
        $this->activityLog->activity = $message;
        $this->activityLog->save();
    }

}
