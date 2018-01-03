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
    public function banPost ($post) {

        $post->text = self::BAN_POST_TEXT;
        $post->old = 1;
        $post->save();;
    }


    /**
     * clean banned comments and messages from DB every week
     */
    public static function periodicBanInDbClean () {

        $oldestBannedMess = Message::where('old', 1)->where('text', self::BAN_POST_TEXT)
            ->min('message.updated_at');

        $oldestBannedComm = Comment::where('old', 1)->where('text', self::BAN_POST_TEXT)
            ->min('comment.updated_at');;

        if (!is_null($oldestBannedMess) && TimeHelper::weekPassed($oldestBannedMess)) {

            $messages = Message::where('old', 1)
                ->where('text', self::BAN_POST_TEXT)
                ->get();

            $messages->each(function ($mess, $key) {
                $mess->delete();
            });
        }

        if (!is_null($oldestBannedComm) && TimeHelper::weekPassed($oldestBannedComm)) {

            $comment = Comment::where('old', 1)
                ->where('text', self::BAN_POST_TEXT)
                ->get();

            $comment->each(function ($comm, $key) {
                $comm->delete();
            });
        }
    }
}
