<?php
/**
 * Created by PhpStorm.
 * User: bartos
 * Date: 20.12.2017
 * Time: 8:45
 */

namespace App;


class TimeHelper
{

    const
        DAY = 3600 * 24,
        HOUR = 3600,
        MINUTE = 60,
        TWO_MINUTES = 120*120*120,
        WEEK = 3600 * 24 * 7;


    /**
     * Converts message created_at time to elapsed time
     *
     * @param $dateTime
     * @return string
     */
    public static function passedTime ($dateTime) {

        $time = strtotime($dateTime);

        $divide = round((time() - $time));

        if ($divide > self::DAY) {
            return date('H:i:s / d.m.y ',$time);

        } elseif ($divide > self::HOUR) {
            $divide = round($divide / self::HOUR);
            return $divide . ' h.';

        } elseif ($divide < self::HOUR && $divide > self::MINUTE) {
            $divide = round($divide / self::MINUTE);
            return $divide . ' min.';

        } elseif ($divide < self::MINUTE) {
            return $divide . ' sec.';
        }

    }

    /**
     * Check if the message was created less than two minutes ago
     *
     * @param $message
     * @return bool
     */
    public static function lessThanTwoMinutes ($message) {

        if (time() - strtotime($message->created_at) <= self::TWO_MINUTES) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $time
     * @return bool
     */
    public static function weekPassed ($time) {

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
    public static function updated ($message) {

        return $message->updated_at != $message->created_at;

    }
}