<?php

namespace App\Repositories;

abstract class AbstractRepository
{

    const BAN_POST_TEXT = 'post was banned!';


    /**
     * return records where old = 1
     *
     * @param $eloquentClass
     * @return mixed
     */
    public function getOldRecords($eloquentClass)
    {
        return $eloquentClass::where('old', 1)->get();
    }


    /**
     * get oldest record time
     *
     * @param $eloquentClass
     * @return mixed
     */
    public function getOldestRecord($eloquentClass)
    {
        return $eloquentClass::where('old', 1)->min('updated_at');
    }



}