<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * @var string
     */
    protected $table = 'comment';

    /**
     * @var array
     */
    protected $fillable = [
        'text', 'old_id', 'old', 'created_at','message_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user () {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function message () {
        return $this->belongsTo(Message::class);
    }
}
