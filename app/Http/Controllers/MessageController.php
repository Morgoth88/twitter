<?php

namespace App\Http\Controllers;

use App\CrudClasses\Message\MessageDeleter;
use App\CrudClasses\Message\MessageReader;
use App\CrudClasses\Message\MessageUpdater;
use App\CrudClasses\Message\MessageCreator;
use App\Events\MessageBanned;
use App\Events\MessageDeleted;
use App\Events\MessageUpdated;
use App\Events\MessageCreated;
use App\Repositories\UserDataRepository;
use App\services\AdminCheckerService;
use App\services\BanService;
use App\services\JsonResponseService;
use App\services\TimeHelperService;
use Illuminate\Http\Request;
use App\Message;
use App\services\ValidatorService;

class MessageController extends Controller
{


    private $jsonResponse;


    private $timeHelper;


    private $adminChecker;


    /**
     * MessageController constructor.
     * @param JsonResponseService $jsonResponseService
     * @param TimeHelperService $timeHelperService
     * @param AdminCheckerService $adminCheckerService
     */
    public function __construct(JsonResponseService $jsonResponseService,
                                TimeHelperService $timeHelperService,
                                AdminCheckerService $adminCheckerService)
    {
        $this->middleware('auth');
        $this->jsonResponse = $jsonResponseService;
        $this->timeHelper = $timeHelperService;
        $this->adminChecker = $adminCheckerService;
    }


    /**
     * Read all messages...5 per page
     *
     * @param MessageReader $messageReader
     * @return mixed
     */
    public function read(MessageReader $messageReader)
    {
        return $this->jsonResponse->okResponse(
            $messageReader->readPost()
        );
    }


    /**
     * create message
     *
     * @param MessageCreator $messageCreator
     * @param ValidatorService $validator
     * @param Request $request
     * @return mixed
     */
    public function create(MessageCreator $messageCreator,
                           ValidatorService $validator,
                           Request $request)
    {
        $validator->ValidateMessage($request);

        $message = $messageCreator->createPost($request);
        event(new MessageCreated($message));

        return $this->jsonResponse->createdResponse($message);
    }


    /**
     * @param MessageUpdater $messageUpdater
     * @param ValidatorService $validator
     * @param Request $request
     * @param Message $message
     * @return mixed
     */
    public function update(MessageUpdater $messageUpdater,
                           ValidatorService $validator,
                           Request $request, Message $message)
    {
        $this->authorize('updateDelete', $message);

        if ($this->timeHelper->lessThanTwoMinutes($message->created_at)) {
            $validator->ValidateMessage($request);

            $newMessage = $messageUpdater->updatePost($request, $message);
            event(new MessageUpdated($newMessage));

            return $this->jsonResponse->okResponse($newMessage);
        } else {
            return $this->jsonResponse->timeExpiredResponse();
        }
    }


    /**
     * message delete
     *
     * @param MessageDeleter $messageDeleter
     * @param Message $message
     * @return mixed
     */
    public function delete(MessageDeleter $messageDeleter,
                           Message $message)
    {
        $this->authorize('updateDelete', $message);

        if ($this->timeHelper->lessThanTwoMinutes($message->created_at)) {
            event(new MessageDeleted($message));

            $id = $messageDeleter->deletePost($message);

            return $this->jsonResponse->okResponse("message : Tweet $id was deleted");
        } else {
            return $this->jsonResponse->timeExpiredResponse();
        }
    }


    /**
     * ban message
     *
     * @param BanService $banService
     * @param UserDataRepository $userDataRepository
     * @param Message $message
     * @param Request $request
     * @return mixed
     */
    public function ban(BanService $banService,
                        UserDataRepository $userDataRepository,
                        Message $message,
                        Request $request)
    {
        $messageUser = $userDataRepository->getUserById($message->user_id);

        if ($this->adminChecker->isAdmin($request->user()) &&
            !$this->adminChecker->isAdmin($messageUser)) {

            $banService->banMessage($message);

            event(new MessageBanned($message));

            return $this->jsonResponse->okResponse("message : Tweet $message->id was banned");
        } else {
            return $this->jsonResponse->unauthorizedResponse();
        }
    }

}
