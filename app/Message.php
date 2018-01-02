<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{


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
        'text', 'old_id', 'old', 'created_at','updated_at',
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

}
