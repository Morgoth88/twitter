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
use App\Exceptions\DataErrorException;
use App\Exceptions\TimeExpiredException;
use App\Exceptions\UserRoleException;
use App\Interfaces\MessageInterface;
use App\Repositories\UserDataRepository;
use App\Services\AdminCheckerService;
use App\Services\BanService;
use App\Services\JsonResponseService;
use App\Services\TimeHelperService;
use Illuminate\Http\Request;
use App\Message;
use App\Services\ValidatorService;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class MessageController extends Controller implements MessageInterface
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
        $this->middleware(['auth', 'revalidate']);
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
        try {
            return $this->jsonResponse
                ->okResponse($messageReader->allPosts());

        } catch (DataErrorException $dataErrorException) {
            return $this->jsonResponse
                ->exceptionResponse($dataErrorException
                    ->getMessage(), 500);
        }
    }


    /**
     * create message
     *
     * @param MessageCreator $messageCreator
     * @param Request $request
     * @return mixed
     */
    public function create(MessageCreator $messageCreator,
                           Request $request)
    {
        try {
            $this->validator->validateMessage($request->all());

            $message = $messageCreator->createPost($request);
            event(new MessageCreated($message));

            return $this->jsonResponse->createdResponse($message);

        } catch (ValidationException $validationException) {
            return $this->jsonResponse
                ->exceptionResponse($validationException
                    ->getMessage(), 422);

        } catch (DataErrorException $dataErrorException) {
            return $this->jsonResponse
                ->exceptionResponse($dataErrorException
                    ->getMessage(), 500);
        }
    }


    /**
     * update Message
     *
     * @param MessageUpdater $messageUpdater
     * @param Request $request
     * @param Message $message
     * @return mixed
     */
    public function update(MessageUpdater $messageUpdater,
                           Request $request,
                           Message $message)
    {
        $this->authorize('changeMessage', $message);

        try {
            $this->timeHelper->lessThanTwoMinutes($message->created_at);

            $this->validator->validateMessage($request->all());

            $newMessage = $messageUpdater->updatePost($request, $message);
            event(new MessageUpdated($newMessage));

            return $this->jsonResponse->okResponse($newMessage);

        } catch (TimeExpiredException $expiredException) {
            return $this->jsonResponse
                ->exceptionResponse($expiredException
                    ->getMessage(), 408);

        } catch (ValidationException $validationException) {
            return $this->jsonResponse
                ->exceptionResponse($validationException
                    ->getMessage(), 422);

        } catch (DataErrorException $dataErrorException) {
            return $this->jsonResponse
                ->exceptionResponse($dataErrorException
                    ->getMessage(), 500);
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
        $this->authorize('changeMessage', $message);

        try {
            $this->timeHelper->lessThanTwoMinutes($message->created_at);

            event(new MessageDeleted($message));
            $id = $messageDeleter->deletePost($message);

            return $this->jsonResponse->okResponse("message : Tweet $id was deleted");

        } catch (TimeExpiredException $expiredException) {
            return $this->jsonResponse
                ->exceptionResponse($expiredException
                    ->getMessage(), 408);

        } catch (DataErrorException $dataErrorException) {
            return $this->jsonResponse
                ->exceptionResponse($dataErrorException
                    ->getMessage(), 500);
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
        try {
            $messageUser = $userDataRepository->getUserById($message->user_id);

            $this->adminChecker->isAdmin($request->user());
            $this->adminChecker->isUser($messageUser);

            $banService->banMessage($message);
            event(new MessageBanned($message));

            return $this->jsonResponse
                ->okResponse("message : Tweet $message->id was banned");

        } catch (UserRoleException $roleException) {
            return $this->jsonResponse
                ->exceptionResponse($roleException
                    ->getMessage(), 409);
        }
    }

}
