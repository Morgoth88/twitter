<?php

namespace App\Listeners;

use App\Events\MessageUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class messageUpdateListener
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
     * @param  MessageUpdated  $event
     * @return void
     */
    public function handle(MessageUpdated $event)
    {
        //
    }
}
