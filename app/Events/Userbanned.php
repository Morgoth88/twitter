<?php

namespace App\Events;


use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Userbanned implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct ($user){
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn () {
        return new PrivateChannel('user');
    }

    public function broadcastAs () {
        return 'userBan';
    }

    public function broadcastWith () {

        $result= [];

        foreach ($this->user->comment as $comment){
            $result['CmntIds'][] =$comment->id;
        }

        foreach ($this->user->message as $message){
            $result['msgIds'][] =$message->id;
        }

        return [
            'user' => [
                'user_id' => $this->user->id,
                'messages' => $result['msgIds'],
                'comments' => $result['CmntIds'],
            ]
        ];
    }
}
