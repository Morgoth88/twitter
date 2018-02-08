<?php

namespace App\Listeners;

use App\Events\MessageDeleted;
use Illuminate\Support\Facades\Log;

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
        Log::notice('Message was deleted', [
            'id' => $event->message->id,
            'user id' => $event->message->user_id
        ]);
    }
}
