<?php

namespace App\Interfaces;

use App\CrudClasses\Comment\CommentCreator;
use App\CrudClasses\Comment\CommentDeleter;
use App\CrudClasses\Comment\CommentReader;
use App\CrudClasses\Comment\CommentUpdater;
use App\Repositories\UserDataRepository;
use App\Services\BanService;
use Illuminate\Http\Request;
use App\Message;
use App\Comment;

interface CommentInterface
{

    public function read(CommentReader $commentReader,
                         Message $message);


    public function create(CommentCreator $commentCreator,
                           Message $message,
                           Request $request);


    public function delete(CommentDeleter $commentDeleter,
                           Message $message,
                           Comment $comment);


    public function update(CommentUpdater $commentUpdater,
                           Request $request,
                           Message $message,
                           Comment $comment);


    public function ban(BanService $banService,
                        UserDataRepository $dataRepository,
                        Request $request,
                        Message $message,
                        Comment $comment);
}