<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class newCommentCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;
    public $user;
    public $commentCount;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($comment, $user, $commentCount)
    {
        $this->comment = $comment;
        $this->user = $user;
        $this->commentCount = $commentCount;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('comment');
    }

    /**
     * @return string
     */
    public function broadcastAs () {
        return 'newComment';
    }

    /**
     * @return array
     */
    public function broadcastWith () {
        return [
            'comment' => [
                'id' => $this->comment->id,
                'message_id' => $this->comment->message_id,
                'old' => $this->comment->old,
                'text' => $this->comment->text,
                'created_at' => $this->comment->created_at,
                'updated_at' => $this->comment->updated_at,
            ],
            'user' => [
                'user_id' => $this->user->id,
                'userName' => $this->user->name,
                'userRole' => $this->user->role_id,
            ],
            'commentCount' => $this->commentCount
        ];
    }
}
