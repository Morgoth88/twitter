<?php

namespace App\Listeners;

use App\Events\CommentDeleted;


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
        //
    }
}
