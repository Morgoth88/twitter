<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageDeleted implements ShouldBroadcast
{

    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $message;


    /**
     * MessageDeleted constructor.
     * @param $message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('messageDelete');
    }


    public function broadcastAs()
    {
        return 'msgDel';
    }


    public function broadcastWith()
    {
        return [
            'message' => [
                'id' => $this->message
            ]
        ];
    }
}
