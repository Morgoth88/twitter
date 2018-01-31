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
use App\Exceptions\ValidatorException;
use App\Repositories\UserDataRepository;
use App\Services\AdminCheckerService;
use App\Services\BanService;
use App\Services\JsonResponseService;
use App\Services\TimeHelperService;
use Illuminate\Http\Request;
use App\Message;
use App\Services\ValidatorService;

class MessageController extends Controller
{

    private $jsonResponse;

    private $timeHelper;

    private $adminChecker;

    private $validator;


    /**
     * MessageController constructor.
     * @param JsonResponseService $jsonResponseService
     * @param TimeHelperService $timeHelperService
     * @param ValidatorService $validator
     * @param AdminCheckerService $adminCheckerService
     */
    public function __construct(JsonResponseService $jsonResponseService,
                                TimeHelperService $timeHelperService,
                                AdminCheckerService $adminCheckerService,
                                ValidatorService $validator)
    {
        $this->middleware('auth');
        $this->jsonResponse = $jsonResponseService;
        $this->timeHelper = $timeHelperService;
        $this->adminChecker = $adminCheckerService;
        $this->validator = $validator;
    }


    /**
     * Read all messages...5 per page
     *
     * @param MessageReader $messageReader
     * @return mixed
     */
    public
    function read(MessageReader $messageReader)
    {
        return $this->jsonResponse->okResponse(
            $messageReader->readPost()
        );
    }


    /**
     * create message
     *
     * @param MessageCreator $messageCreator
     * @param Request $request
     * @return mixed
     */
    public
    function create(MessageCreator $messageCreator,
                    Request $request)
    {
        $this->validator->validateMessage($request->all());

        $message = $messageCreator->createPost($request);
        event(new MessageCreated($message));

        return $this->jsonResponse->createdResponse($message);
    }


    /**
     * update Message
     *
     * @param MessageUpdater $messageUpdater
     * @param Request $request
     * @param Message $message
     * @return mixed
     */
    public
    function update(MessageUpdater $messageUpdater,
                    Request $request, Message $message)
    {
        $this->authorize('updateDelete', $message);

        if ($this->timeHelper->lessThanTwoMinutes($message->created_at)) {

            $this->validator->validateMessage($request->all());

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
    public
    function delete(MessageDeleter $messageDeleter,
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
    public
    function ban(BanService $banService,
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
