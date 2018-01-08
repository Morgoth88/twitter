<?php

namespace App\Listeners;

use App\Events\MessageDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class messageDeleteListener
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
     * @param  MessageDeleted  $event
     * @return void
     */
    public function handle(MessageDeleted $event)
    {
        //
    }
}
