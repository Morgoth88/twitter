<?php

namespace App\Http\Controllers;

use App\Events\MessageBanned;
use App\Events\MessageDeleted;
use App\Events\MessageUpdated;
use App\Events\MessageCreated;
use App\Repositories\MessageDataRepository;
use App\Repositories\UserDataRepository;
use App\services\AdminCheckerService;
use App\services\BanService;
use App\services\JsonResponseService;
use App\services\TimeHelperService;
use App\Interfaces\MessageInterface;
use Illuminate\Http\Request;
use App\Message;
use App\services\ValidatorService;

class MessageController extends Controller implements MessageInterface
{

    private $messageRepo;


    private $jsonResponseService;


    private $validatorService;


    private $timeHelper;


    private $userRepo;


    private $banService;


    private $adminChecker;


    /**
     * MessageController constructor.
     * @param MessageDataRepository $messageRepo
     * @param JsonResponseService $jsonResponseService
     * @param ValidatorService $validatorService
     * @param TimeHelperService $timeHelperService
     * @param UserDataRepository $userDataRepository
     * @param BanService $banService
     * @param AdminCheckerService $adminCheckerService
     */
    public function __construct(MessageDataRepository $messageRepo,
                                JsonResponseService $jsonResponseService,
                                ValidatorService $validatorService,
                                TimeHelperService $timeHelperService,
                                UserDataRepository $userDataRepository,
                                BanService $banService,
                                AdminCheckerService $adminCheckerService)
    {
        $this->middleware('auth');
        $this->messageRepo = $messageRepo;
        $this->jsonResponseService = $jsonResponseService;
        $this->validatorService = $validatorService;
        $this->timeHelper = $timeHelperService;
        $this->userRepo = $userDataRepository;
        $this->banService = $banService;
        $this->adminChecker = $adminCheckerService;


    }


    /**
     * Read all messages...5 per page
     *
     * @return $this
     */
    public function read()
    {
        return $this->jsonResponseService->okResponse(
            $this->messageRepo->getAllPosts()
        );
    }


    /**
     * create message
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        $this->validatorService->ValidateMessage($request);

        $tweet = $this->messageRepo->createPost($request);

        event(new MessageCreated($tweet, $tweet->user));

        return $this->jsonResponseService->createdResponse($tweet);

    }


    /**
     * update message
     *
     * @param Request $request
     * @param Message $message
     * @return mixed
     */
    public function update(Request $request, Message $message)
    {
        $this->authorize('updateDelete', $message);

        if ($this->timeHelper->lessThanTwoMinutes($message->created_at)) {
            $this->validatorService->ValidateMessage($request);

            $newMessage = $this->messageRepo->updatePost($request, $message);

            event(new MessageUpdated($newMessage, $newMessage->user));

            return $this->jsonResponseService->okResponse($newMessage);
        } else {
            return $this->jsonResponseService->timeExpiredResponse();
        }
    }


    /**
     * Delete message
     *
     * @param Message $message
     * @return mixed
     */
    public function delete(Message $message)
    {
        $this->authorize('updateDelete', $message);

        if ($this->timeHelper->lessThanTwoMinutes($message->created_at)) {
            $id = $message->id;
            $message->delete();

            event(new MessageDeleted($id));

            return $this->jsonResponseService->okResponse("message : Tweet $id was deleted");
        } else {
            return $this->jsonResponseService->timeExpiredResponse();
        }
    }


    /**
     * ban message
     *
     * @param Message $message
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function ban(Message $message, Request $request)
    {
        $user = $this->userRepo->getUserById($message->user_id);

        if ($this->adminChecker->isAdmin($request->user()) &&
            !$this->adminChecker->isAdmin($user)) {

            $this->banService->banMessage($message);

            event(new MessageBanned($message));

            return $this->jsonResponseService->okResponse("message : Tweet $message->id was banned");

        } else {
            return  $this->jsonResponseService->unauthorizedResponse();
        }

    }
}