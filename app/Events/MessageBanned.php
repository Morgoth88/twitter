<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageBanned implements ShouldBroadcast
{

    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $message;


    /**
     * MessageBanned constructor.
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
        return new PrivateChannel('messageBanned');
    }


    public function broadcastAs()
    {
        return 'msgBan';
    }


    public function broadcastWith()
    {
        return [
            'message' => [
                'id' => $this->message->id
            ]
        ];
    }
}