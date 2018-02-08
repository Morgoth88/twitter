<?php

namespace App\Listeners;

use App\Events\CommentDeleted;
use Illuminate\Support\Facades\Log;

class CommentDeletedListener
{

    /**
     * commentDeleteListener constructor.
     */
    public function __construct()
    {
        //
    }


    /**
     * Handle the event.
     *
     * @param  CommentDeleted $event
     * @return void
     */
    public function handle(CommentDeleted $event)
    {
        Log::notice('Comment was deleted', [
            'id' => $event->comment->id,
            'user id' => $event->comment->user_id
        ]);
    }
}
