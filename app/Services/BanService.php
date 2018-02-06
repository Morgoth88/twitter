<?php

namespace App\Services;

use App\Repositories\CommentDataRepository;
use App\Repositories\MessageDataRepository;
use App\Repositories\SessionDataRepository;
use App\Repositories\UserDataRepository;

class BanService
{

    private $commentDataRepository;

    private $messageDataRepository;

    private $userDataRepository;

    private $sessionDataRepository;


    /**
     * BanService constructor.
     *
     * @param MessageDataRepository $messageRepo
     * @param CommentDataRepository $commentRepo
     * @param UserDataRepository $userRepo
     * @param SessionDataRepository $sessionRepo
     */
    public function __construct(MessageDataRepository $messageRepo,
                                CommentDataRepository $commentRepo,
                                UserDataRepository $userRepo,
                                SessionDataRepository $sessionRepo)
    {
        $this->messageDataRepository = $messageRepo;
        $this->commentDataRepository = $commentRepo;
        $this->userDataRepository = $userRepo;
        $this->sessionDataRepository = $sessionRepo;
    }


    /**
     * @param $comment
     */
    public function banComment($comment)
    {
        $this->commentDataRepository->ban($comment);
    }


    /**
     * @param $message
     */
    public function banMessage($message)
    {
        $this->messageDataRepository->ban($message);
        $this->messageDataRepository->banAll($message->comment);
    }


    /**
     * @param $user
     */
    public function banUser($user)
    {
        $this->sessionDataRepository->deleteUserSession($user);

        $this->messageDataRepository->banAll($user->message);
        $this->commentDataRepository->banAll($user->comment);

        $this->userDataRepository->banUser($user);
    }

}