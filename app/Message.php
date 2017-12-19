<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    const
        DAY = 3600 * 24,
        HOUR = 3600,
        MINUTE = 60;

    /**
     * @var string
     */
    protected $table = 'message';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text', 'old_id', 'user_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user () {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comment () {
        return $this->hasMany(Comment::class);
    }

    public static function passedTime ($dateTime) {

        $time = strtotime($dateTime);

        $divide = round((time() - $time));

        if ($divide > self::DAY) {
            return $dateTime;

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

    public static function lessThanTwoMinutes ($message) {

        if (time() - strtotime($message->created_at) <= 3600) {

            return true;
        } else {
            return false;
        }
    }
}
