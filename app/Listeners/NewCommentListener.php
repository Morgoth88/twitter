<?php

namespace App\Listeners;

use App\Events\CommentCreated;
use Illuminate\Support\Facades\Log;

class NewCommentListener
{

    /**
     * newCommentListener constructor.
     */
    public function __construct()
    {
        //
    }


    /**
     * Handle the event.
     *
     * @param  CommentCreated $event
     * @return void
     */
    public function handle(CommentCreated $event)
    {
        Log::notice('New comment was created', [
            'id' => $event->comment->id,
            'user id' => $event->comment->user_id
        ]);
    }
}
