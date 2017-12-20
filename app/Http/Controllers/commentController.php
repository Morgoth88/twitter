<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Message;
use Illuminate\Http\Request;
use App\Interfaces\CommentInterface;

class commentController extends Controller implements CommentInterface
{
    public function read () {
        // TODO: Implement read() method.
    }

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
        return redirect(route('readTweet')) ;
    }

    public function update (Request $request, Comment $comment) {
        // TODO: Implement update() method.
    }

    public function delete (Request $request, Comment $comment) {
        // TODO: Implement delete() method.
    }
}
