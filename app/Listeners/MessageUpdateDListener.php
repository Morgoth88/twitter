<?php

namespace App\Listeners;

use App\Events\MessageUpdated;
use Illuminate\Support\Facades\Log;

class MessageUpdateDListener
{

    /**
     * messageUpdateListener constructor.
     */
    public function __construct()
    {
        //
    }


    /**
     * Handle the event.
     *
     * @param  MessageUpdated $event
     * @return void
     */
    public function handle(MessageUpdated $event)
    {
        Log::notice('Message was updated', [
            'id' => $event->message->id,
            'user id' => $event->message->user_id
        ]);
    }
}
