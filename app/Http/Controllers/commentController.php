<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Message;
use Illuminate\Http\Request;
use App\Interfaces\CommentInterface;
use App\TimeHelper;

class commentController extends Controller implements CommentInterface
{


    /**
     * Show message with comments
     *
     * @param Message $message
     * @return $this
     */
    public function read (Message $message) {

        $message->comment = $message->comment->sortByDesc('created_at');

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

        $request->session()->flash('status', 'Comment has been successfully created');
        return redirect(route('readTweet'));
    }


    public function update (Request $request,Message $message, Comment $comment) {
             
             
             
                 if (TimeHelper::lessThanTwoMinutes($comment)) {
                          
               $this->validate($request, [
            'comment' => 'required|string'],
            ['Comment is empty!']);
                        
             $newComment = $request->user()->comment()->create([
                 'text' => $request->comment,
                 'old_id' => $comment->id,
                 'created_at' => $comment->created_at,
                 'message_id'  => $message->id            ]);
             
             $comment->old = 1;
             $comment->save();
             
             $request->session()->flash('status', 'Comment was successfully updated');
            return redirect(route('readTweet'));
                 } else {
            $request->session()->flash('status', 'Sorry, time to update has expired');
            return redirect(route('readTweet'));
    }
    }


    public function delete (Request $request, Message $message, Comment $comment) {

        $this->authorize('update_delete_comm', $comment);

        if(TimeHelper::lessThanTwoMinutes($comment)){

            $comment->delete();

            $request->session()->flash('status', 'Comment was successfully deleted');
            return redirect(route('readTweet'));
        }
    }
}
