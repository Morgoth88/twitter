<?php

namespace App\Listeners;

use App\Events\MessageCreated;
use Illuminate\Support\Facades\Log;

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
        Log::notice('New message was created', [
            'id' => $event->message->id,
            'user id' => $event->message->user_id
        ]);
    }
}
