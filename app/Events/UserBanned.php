<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserBanned implements ShouldBroadcast
{

    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $user;


    /**
     * UserBanned constructor.
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user');
    }


    public function broadcastAs()
    {
        return 'userBan';
    }


    public function broadcastWith()
    {
        return [
            'user' => [
                'user_id' => $this->user->id,
                'messages' => $this->user->message,
                'comments' => $this->user->comment,
            ]
        ];
    }

}
