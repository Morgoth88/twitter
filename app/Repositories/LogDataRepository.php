<?php

namespace App\Repositories;

use App\ActivityLog;

class LogDataRepository
{

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



}