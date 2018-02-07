<?php

namespace App\Policies;

use App\Comment;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{

    use HandlesAuthorization;


    /**
     * CommentPolicy constructor.
     */
    public function __construct()
    {
        //
    }


    /**
     * @param User $user
     * @param Comment $comment
     * @return bool
     */
    public function changeComment(User $user, Comment $comment)
    {
        return $user->id === $comment->user->id;
    }
}
