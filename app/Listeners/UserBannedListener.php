<?php

namespace App\Listeners;

use App\Events\UserBanned;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserBannedListener
{

    /**
     * userBannedListener constructor.
     */
    public function __construct()
    {
        //
    }


    /**
     * Handle the event.
     *
     * @param  UserBanned $event
     * @return void
     */
    public function handle(UserBanned $event)
    {
        Log::notice('User was banned', [
            'admin id' => Auth::user()->id,
            'banned user id' => $event->user->id
        ]);
    }
}
