<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CommentBanned implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $comment;
    public $commentCount;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($comment, $commentCount)
    {
        $this->comment =  $comment;
        $this->commentCount = $commentCount;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('commentBanned');
    }

    /**
     * @return string
     */
    public function broadcastAs () {
        return 'cmntBan';
    }

    /**
     * @return array
     */
    public function broadcastWith () {
        return [
            'comment' => [
                'id' => $this->comment->id,
                'message_id' => $this->comment->message_id
            ],
            'commentCount' => $this->commentCount
        ];
    }
}
