<?php

namespace App\Services;

use App\Exceptions\TimeExpiredException;
use InvalidArgumentException;

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
     * @throws TimeExpiredException
     */
    public function lessThanTwoMinutes($createdAt)
    {
        if (!$createdAt || $createdAt === true)
            throw new \InvalidArgumentException('Invalid argument');

        if (time() - strtotime($createdAt) <= self::TWO_MINUTES &&
            time() - strtotime($createdAt) >= 0) {
            return true;
        } else {
            throw new TimeExpiredException('Time limit expired');
        }
    }


    /**
     * @param $time
     * @return bool
     */
    public function weekPassed($time)
    {
        if (!$time || $time === true)
            throw new InvalidArgumentException('Invalid argument');

        if ((time() - strtotime($time)) >= self::WEEK) {
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