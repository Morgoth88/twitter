<?php

namespace App\Listeners;

use App\Events\MessageDeleted;


class MessageDeletedListener
{

    /**
     * messageDeleteListener constructor.
     */
    public function __construct()
    {
        //
    }


    /**
     * Handle the event.
     *
     * @param  MessageDeleted $event
     * @return void
     */
    public function handle(MessageDeleted $event)
    {
        //
    }
}
