<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Message;
use App\Comment;
use App\TimeHelper;

class Ban extends Model
{

    const BAN_POST_TEXT = 'post was banned!';

    /**
     * ban post
     * @param $post
     */
    public function banCmnt ($post) {

        $post->text = self::BAN_POST_TEXT;
        $post->old = 1;
        $post->save();;
    }

    /**
     * @param $post
     */
    public function banMsg($post) {

        $post->text = self::BAN_POST_TEXT;
        $post->old = 1;
        foreach ($post->comment as &$comm)
        {
            $comm->old = 1;
            $comm->save();
        }
        $post->save();;
    }

}
