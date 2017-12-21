<?php

namespace App\Policies;

use App\Comment;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Check if comment belongs to user
     *
     * @param User $user
     * @param Message $message
     * @return bool
     */
    public function update_delete_comm(User $user, Comment $comment){
        return $user->id === $comment->user->id;
    }
}
