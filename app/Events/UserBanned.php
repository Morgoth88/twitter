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

        $result = [];

        if (count($this->user->comment) > 0) {
            foreach ($this->user->comment as $comment) {
                $result['CmntIds'][] = $comment->id;
            }
        }

        if (count($this->user->message) > 0) {
            foreach ($this->user->message as $message) {
                $result['msgIds'][] = $message->id;
            }
        }

        return [
            'user' => [
                'user_id' => $this->user->id,
                'messages' => ($result['msgIds']) ? $result['msgIds'] : '',
                'comments' => ($result['CmntIds']) ? $result['CmntIds'] : '',
            ]
        ];
    }
}
