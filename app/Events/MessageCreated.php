<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageCreated implements ShouldBroadcast
{

    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $message;


    public $user;


    public $requestUser;


    /**
     * newMessageCreated constructor.
     * @param $message
     * @param $user
     */
    public function __construct($message, $user)
    {
        $this->message = $message;
        $this->user = $user;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('message');
    }


    public function broadcastAs()
    {
        return 'newMessage';
    }


    public function broadcastWith()
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'old' => $this->message->old,
                'text' => $this->message->text,
                'created_at' => $this->message->created_at,
                'updated_at' => $this->message->updated_at,
            ],
            'user' => [
                'user_id' => $this->user->id,
                'userName' => $this->user->name,
                'userRole' => $this->user->role_id,
            ]
        ];
    }
}
