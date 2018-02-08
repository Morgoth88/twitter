<?php

namespace App\Listeners;

use App\Events\CommentBanned;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentBannedListener
{

    /**
     * commentBannedListener constructor.
     */
    public function __construct()
    {
        //
    }


    /**
     * Handle the event.
     *
     * @param  CommentBanned $event
     * @return void
     */
    public function handle(CommentBanned $event)
    {
        Log::notice('Comment was banned', [
            'admin id' => Auth::user()->id,
            'comment id' => $event->comment->id,
            'comment user id' => $event->comment->user_id
        ]);
    }
}
