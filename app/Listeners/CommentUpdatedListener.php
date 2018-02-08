<?php

namespace App\Listeners;

use App\Events\CommentUpdated;
use Illuminate\Support\Facades\Log;

class CommentUpdatedListener
{

    /**
     * commentUpdateListener constructor.
     */
    public function __construct()
    {
        //
    }


    /**
     * Handle the event.
     *
     * @param  CommentUpdated $event
     * @return void
     */
    public function handle(CommentUpdated $event)
    {
        Log::notice('Comment was updated', [
            'id' => $event->comment->id,
            'user id' => $event->comment->user_id
        ]);
    }
}
