<?php

namespace App\Listeners;

use App\Events\newMessageCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class newMessageListener
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
     * @param  newMessageCreated  $event
     * @return void
     */
    public function handle(newMessageCreated $event)
    {
        //
    }
}
