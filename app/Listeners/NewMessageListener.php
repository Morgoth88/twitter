<?php

namespace App\Listeners;

use App\Events\MessageCreated;

class NewMessageListener
{

    /**
     * newMessageListener constructor.
     */
    public function __construct()
    {
        //
    }


    /**
     * Handle the event.
     *
     * @param  MessageCreated $event
     * @return void
     */
    public function handle(MessageCreated $event)
    {
        //
    }
}
