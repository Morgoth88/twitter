<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TimeHelper;

class Activity_log extends Model
{


    /**
     * @var string
     */
    protected $table = 'activity_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'activity', 'user_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user () {
        return $this->belongsTo(User::class);
    }

    /**
     * Periodic log delete every week
     */
    public static function periodic_log_delete () {

        $oldestLogTime = Activity_log::min('created_at');

        if (TimeHelper::weekPassed($oldestLogTime)) {

            $logs = Activity_log::where('created_at', '>=', Activity_log::min('created_at'))->delete();
        }
    }
}
