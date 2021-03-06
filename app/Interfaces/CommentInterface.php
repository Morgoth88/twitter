<?php
/**
 * Created by PhpStorm.
 * User: bartos
 * Date: 20.12.2017
 * Time: 12:13
 */

namespace App\Interfaces;

use Illuminate\Http\Request;
use App\Message;
use App\Comment;

interface CommentInterface
{
    public function read(Message $message);
    public function create(Message $message, Request $request);
    public function delete(Request $request, Message $message, Comment $comment);
    public function update(Request $request,Message $message, Comment $comment);
}