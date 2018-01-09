<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Events\CommentBanned;
use App\Events\CommentDeleted;
use App\Events\CommentUpdated;
use App\Events\newCommentCreated;
use App\Message;
use Illuminate\Http\Request;
use App\Interfaces\CommentInterface;
use App\TimeHelper;
use App\User;
use App\Ban;

class commentController extends Controller implements CommentInterface
{

    const SUCC_COM_CRT = 'Comment has been successfully created',
        SUCC_COM_UPDT = 'Comment has been successfully updated',
        TIME_EXP = 'Sorry, time limit expired!',
        SUCC_COM_DEL = 'Comment was successfully deleted',
        SUCC_COM_BAN = 'Comment was successfully banned',
        UNAUTH = 'Unauthorized action';

    /**
     * Show message with comments
     *
     * @param Message $message
     * @return $this
     */
    public function read (Message $message) {

        $message->comment = $message->comment->where('old', 0)->sortByDesc('created_at');

        return view('message-comment')->with('tweet', $message);
    }


    /**
     * Create comment
     *
     * @param Message $message
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create (Message $message, Request $request) {

        $this->validate($request, [
            'comment' => 'required|string'],
            ['Comment is empty!']);

        $comment = $request->user()->comment()
            ->create([
                'text' => $request->comment,
                'message_id' => $message->id
            ]);

        $message->updated_at = now();
        $message->save();

        event(new newCommentCreated($comment, $comment->user));

        $request->session()->flash('status', self::SUCC_COM_CRT);
        return redirect(route('readTweet'));
    }


    /**
     * update comment
     *
     * @param Request $request
     * @param Message $message
     * @param Comment $comment
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update (Request $request, Message $message, Comment $comment) {


        if (TimeHelper::lessThanTwoMinutes($comment)) {

            $this->validate($request, [
                'comment' => 'required|string'],
                ['Comment is empty!']);

            $newComment = $request->user()->comment()->create([
                'text' => $request->comment,
                'old_id' => $comment->id,
                'created_at' => $comment->created_at,
                'message_id' => $message->id]);

            $comment->old = 1;
            $comment->save();

            event(new CommentUpdated($newComment, $newComment->user));

            $request->session()->flash('status', self::SUCC_COM_UPDT);
            return redirect(route('readTweet'));
        } else {
            $request->session()->flash('error', self::TIME_EXP);
            return redirect(route('readTweet'));
        }
    }


    /**
     * delete comment
     *
     * @param Request $request
     * @param Message $message
     * @param Comment $comment
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete (Request $request, Message $message, Comment $comment) {

        $this->authorize('update_delete_comm', $comment);

        if (TimeHelper::lessThanTwoMinutes($comment)) {

            event(new CommentDeleted($comment));

            $comment->delete();

            $request->session()->flash('status', self::SUCC_COM_DEL);
            return redirect(route('readTweet'));
        } else {
            $request->session()->flash('error', self::TIME_EXP);
            return redirect(route('readTweet'));
        }
    }


    /**
     * Comment ban
     *
     * @param Request $request
     * @param Message $message
     * @param Comment $comment
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function ban(Request $request, Message $message, Comment $comment){

        $user = User::where('id', $comment->user_id)->first();
        $ban = new Ban();

        if($request->user()->role_id == 1 && $user->role_id != 1){

            $ban->banPost($comment);

            event(new CommentBanned($comment));

            $request->session()->flash('status',self::SUCC_COM_BAN);
            return redirect(route('readTweet'));
        }
        else{
            $request->session()->flash('error',self::UNAUTH);
            return redirect(route('readTweet'));
        }

    }
}
