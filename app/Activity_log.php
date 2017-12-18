<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

    public function periodic_log_delete () {

        $oldestLog = strtotime(Activity_log::min('created_at'));

        if ((time() - $oldestLog) >= 1) {
            $logs = Activity_log::where('created_at', '>', Activity_log::min('created_at'))->get();

            $logs->delete();
        }
        ;
    }
}
