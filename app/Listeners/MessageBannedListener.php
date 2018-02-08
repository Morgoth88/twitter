<?php

namespace App\Listeners;

use App\Events\MessageBanned;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MessageBannedListener
{

    /**
     * messageBannedListener constructor.
     */
    public function __construct()
    {
        //
    }


    /**
     * Handle the event.
     *
     * @param  MessageBanned $event
     * @return void
     */
    public function handle(MessageBanned $event)
    {
        Log::notice('Message was banned', [
            'admin id' => Auth::user()->id,
            'message id' => $event->message->id,
            'message user id' => $event->message->user_id
        ]);
    }
}
