<?php

namespace App\services;

class TimeHelperService
{

    const
        DAY = 3600 * 24,
        HOUR = 3600,
        MINUTE = 60,
        TWO_MINUTES = 120,
        WEEK = 3600 * 24 * 7;


    /**
     * Check if the message was created less than two minutes ago
     *
     * @param $createdAt
     * @return bool
     */
    public function lessThanTwoMinutes($createdAt)
    {
        if (time() - strtotime($createdAt) <= self::TWO_MINUTES) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * @param $time
     * @return bool
     */
    public function weekPassed($time)
    {
        $time = strtotime($time);

        if ((time() - $time) >= self::WEEK) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * @param $message
     * @return bool
     */
    public static function updated($message)
    {
        return $message->updated_at != $message->created_at;
    }

}