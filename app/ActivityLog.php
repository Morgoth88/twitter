<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
