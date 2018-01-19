<?php

namespace App\Listeners;

use App\Events\Userbanned;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class userBannedListener
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
     * @param  Userbanned  $event
     * @return void
     */
    public function handle(Userbanned $event)
    {
        //
    }
}
