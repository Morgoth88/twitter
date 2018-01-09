<?php

namespace App\Listeners;

use App\Events\CommentBanned;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class commentBannedListener
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
     * @param  CommentBanned  $event
     * @return void
     */
    public function handle(CommentBanned $event)
    {
        //
    }
}
