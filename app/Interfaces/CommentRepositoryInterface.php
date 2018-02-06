<?php

namespace App\Interfaces;

interface CommentRepositoryInterface
{
    public function getAllComments($message);

    public function createComment($request, $message);

    public function updateComment($request, $comment);

    public function getCommentsCount($message);

    public function getOldRecords();

    public function getOldestRecord();

}