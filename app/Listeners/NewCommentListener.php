<?php

namespace App\Listeners;

use App\Events\CommentCreated;


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
        //
    }
}
