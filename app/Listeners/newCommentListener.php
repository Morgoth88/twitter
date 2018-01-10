<?php

namespace App\Listeners;

use App\Events\newCommentCreated;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class newCommentListener
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
     * @param  newCommentCreated  $event
     * @return void
     */
    public function handle(newCommentCreated $event)
    {
        //
    }
}
