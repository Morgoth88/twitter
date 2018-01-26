<?php

namespace App\services;

use App\Repositories\CommentDataRepository;
use App\Repositories\MessageDataRepository;
use App\Repositories\UserDataRepository;
use Illuminate\Support\Facades\DB;

class BanService
{

    /**
     * @var
     */
    private $commentRepo;


    /**
     * @var
     */
    private $messageRepo;


    /**
     * @var
     */
    private $userRepo;


    /**
     * BanService constructor.
     * @param MessageDataRepository $messageRepo
     * @param CommentDataRepository $commentRepo
     * @param UserDataRepository $userRepo
     */
    public function __construct(MessageDataRepository $messageRepo,
                                CommentDataRepository $commentRepo,
                                UserDataRepository $userRepo)
    {
        $this->messageRepo = $messageRepo;
        $this->commentRepo = $commentRepo;
        $this->userRepo = $userRepo;
    }


    /**
     * @param $comments
     */
    public function banComments($comments)
    {
        foreach ($comments as & $comment)
            $this->commentRepo->banComment($comment);
    }


    /**
     * @param $messages
     */
    public function banMessages($messages)
    {
        foreach ($messages as &$message) {
            $this->messageRepo->banMessage($message);
        }
    }


    /**
     * @param $comment
     */
    public function banComment($comment)
    {
        $this->commentRepo->banPost($comment);
    }


    /**
     * @param $message
     */
    public function banMessage($message)
    {
        $this->messageRepo->banPost($message);
    }


    /**
     * @param $user
     */
    public function banUser($user)
    {
        DB::table('sessions')->where('user_id', $user->id)->delete();

        $this->userRepo->banUser($user);
    }

}