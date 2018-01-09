<?php

namespace App\Listeners;

use App\Events\MessageBanned;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class messageBannedListener
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
     * @param  MessageBanned  $event
     * @return void
     */
    public function handle(MessageBanned $event)
    {
        //
    }
}
