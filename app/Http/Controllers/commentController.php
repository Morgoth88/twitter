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
use App\Models\commentModel;

class commentController extends Controller implements CommentInterface
{


    /**
     * messageController constructor.
     */
    public function __construct () {

        $this->middleware('auth');
    }

    /**
     * Show message with comments
     *
     * @param Message $message
     * @return $this
     */
    public function read (Message $message) {

        $commentModel = new commentModel();
        $message = $commentModel->getAllComments($message);

        return response($message->toJson(),200)
            ->header('Content-Type', 'application/json');
    }


    /**
     * Create comment
     *
     * @param Message $message
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create (Message $message, Request $request) {

        $commentModel = new commentModel();

        $this->validate($request, [
            'comment' => 'required|string'],
            ['Comment is empty!']);

        $comment = $commentModel->createComment($request, $message);

        $commentCount = $message->comment()->where('old','0')->count();

        event(new newCommentCreated($comment, $comment->user, $commentCount));

        return response($comment->toJson() , 201)
            ->header('Content-Type', 'application/json');
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

            $commentModel = new commentModel();

            $this->validate($request, [
                'comment' => 'required|string'],
                ['Comment is empty!']);

            $newComment = $commentModel->updateComment($request, $comment, $message);

            event(new CommentUpdated($newComment, $newComment->user));

            return response($newComment->toJson() , 200)
                ->header('Content-Type', 'application/json');

        } else {
            return response(json_encode('message : Time limit to update expired') , 401)
                ->header('Content-Type', 'application/json');
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

            $commentCount = $message->comment()->where('old','0')->count();

            event(new CommentDeleted($comment, $commentCount - 1));

            $id = $comment->id;
            $comment->delete();

            return response(json_encode("message : Tweet $id was deleted"), 200)
                ->header('Content-Type', 'application/json');

        } else {
            return response(json_encode('message : Time limit to update expired') , 401)
                ->header('Content-Type', 'application/json');
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

            $ban->banCmnt($comment);

            $commentCount = $message->comment()->where('old','0')->count();

            event(new CommentBanned($comment, $commentCount));

            return response(json_encode("message : Tweet $comment->id was banned"), 200)
                ->header('Content-Type', 'application/json');

        } else {
            return response(json_encode('message : Unauthorized action') , 401)
                ->header('Content-Type', 'application/json');
        }

    }
}
