<?php

namespace App\Listeners;

use App\Events\CommentUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class commentUpdateListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CommentUpdated  $event
     * @return void
     */
    public function handle(CommentUpdated $event)
    {
        //
    }
}
