<?php

namespace App\Repositories;

abstract class AbstractRepository
{

    const BAN_POST_TEXT = 'post was banned!';


    /**
     * return records where old = 1
     *
     * @param $table
     * @return mixed
     */
    public function getOldRecords($table)
    {
        return $table::where('old', 1)->get();
    }


    /**
     * get oldest record time
     *
     * @param $table
     * @return mixed
     */
    public function getOldestRecord($table)
    {
        return $table::where('old', 1)->min('updated_at');
    }


}