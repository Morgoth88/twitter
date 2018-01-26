<?php

namespace App\Listeners;

use App\Events\CommentUpdated;


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
        //
    }
}
