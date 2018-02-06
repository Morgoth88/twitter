<?php

namespace App\Http\Controllers;

use App\Comment;
use App\CrudClasses\Comment\CommentCreator;
use App\CrudClasses\Comment\CommentDeleter;
use App\CrudClasses\Comment\CommentReader;
use App\CrudClasses\Comment\CommentUpdater;
use App\Events\CommentBanned;
use App\Events\CommentDeleted;
use App\Events\CommentUpdated;
use App\Events\CommentCreated;
use App\Exceptions\DataErrorException;
use App\Interfaces\CommentInterface;
use App\Message;
use Illuminate\Http\Request;
use App\Repositories\UserDataRepository;
use App\Services\AdminCheckerService;
use App\Services\BanService;
use App\Services\JsonResponseService;
use App\Services\TimeHelperService;
use App\Services\ValidatorService;
use Illuminate\Validation\ValidationException;
use App\Exceptions\TimeExpiredException;
use App\Exceptions\UserRoleException;

class CommentController extends Controller implements CommentInterface
{

    private $jsonResponse;

    private $timeHelper;

    private $adminChecker;

    private $validator;


    /**
     * CommentController constructor.
     * @param JsonResponseService $jsonResponseService
     * @param TimeHelperService $timeHelperService
     * @param AdminCheckerService $adminCheckerService
     * @param ValidatorService $validator
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
     * Show message with comments
     *
     * @param CommentReader $commentReader
     * @param Message $message
     * @return mixed
     */
    public function read(CommentReader $commentReader, Message $message)
    {
        try {
            return $this->jsonResponse->okResponse(
                $commentReader->readPost($message)
            );
        } catch (DataErrorException $dataErrorException) {
            return $this->jsonResponse
                ->exceptionResponse($dataErrorException
                    ->getMessage(), 500);
        }
    }


    /**
     * Create comment
     *
     * @param CommentCreator $commentCreator
     * @param Message $message
     * @param Request $request
     * @return mixed
     */
    public function create(CommentCreator $commentCreator,
                           Message $message,
                           Request $request)
    {
        try {
            $this->validator->validateComment($request->all());

            $comment = $commentCreator->createPost($request, $message);
            event(new CommentCreated($comment));

            return $this->jsonResponse->createdResponse($comment);

        } catch (DataErrorException $dataErrorException) {
            return $this->jsonResponse
                ->exceptionResponse($dataErrorException
                    ->getMessage(), 500);

        } catch (ValidationException $validationException) {
            return $this->jsonResponse
                ->exceptionResponse($validationException
                    ->getMessage(), 422);
        }
    }


    /**
     * update comment
     *
     * @param CommentUpdater $commentUpdater
     * @param Request $request
     * @param Message $message
     * @param Comment $comment
     * @return mixed
     */
    public function update(CommentUpdater $commentUpdater,
                           Request $request,
                           Message $message,
                           Comment $comment)
    {
        $this->authorize('updateDeleteComment', $comment);

        try {
            $this->timeHelper->lessThanTwoMinutes($comment->created_at);

            $this->validator->validateComment($request->all());

            $newComment = $commentUpdater->updatePost($request, $comment);
            event(new CommentUpdated($newComment));

            return $this->jsonResponse->okResponse($newComment);

        } catch (DataErrorException $dataErrorException) {
            return $this->jsonResponse
                ->exceptionResponse($dataErrorException
                    ->getMessage(), 500);

        } catch (ValidationException $validationException) {
            return $this->jsonResponse
                ->exceptionResponse($validationException
                    ->getMessage(), 422);

        } catch (TimeExpiredException $expiredException) {
            return $this->jsonResponse
                ->exceptionResponse($expiredException
                    ->getMessage(), 408);

        }
    }


    /**
     * delete comment
     *
     * @param CommentDeleter $commentDeleter
     * @param Message $message
     * @param Comment $comment
     * @return mixed
     */
    public function delete(CommentDeleter $commentDeleter,
                           Message $message,
                           Comment $comment)
    {
        $this->authorize('updateDeleteComment', $comment);

        try {
            $this->timeHelper->lessThanTwoMinutes($comment->created_at);

            event(new CommentDeleted($comment));
            $id = $commentDeleter->deletePost($comment);

            return $this->jsonResponse->okResponse("message : Comment $id was deleted");

        } catch (DataErrorException $dataErrorException) {
            return $this->jsonResponse
                ->exceptionResponse($dataErrorException
                    ->getMessage(), 500);

        } catch (TimeExpiredException $expiredException) {
            return $this->jsonResponse
                ->exceptionResponse($expiredException
                    ->getMessage(), 408);

        }
    }


    /**
     * Comment ban
     *
     * @param BanService $banService
     * @param UserDataRepository $userDataRepository
     * @param Request $request
     * @param Message $message
     * @param Comment $comment
     * @return mixed
     */
    public function ban(BanService $banService,
                        UserDataRepository $userDataRepository,
                        Request $request,
                        Message $message,
                        Comment $comment)
    {
        try {
            $commentUser = $userDataRepository->getUserById($comment->user_id);

            $this->adminChecker->isAdmin($request->user());
            $this->adminChecker->isUser($commentUser);

            $banService->banComment($comment);
            event(new CommentBanned($comment));

            return $this->jsonResponse->okResponse(
                "message : Tweet $comment->id was banned"
            );
        } catch (UserRoleException $roleException) {
            return $this->jsonResponse
                ->exceptionResponse($roleException
                    ->getMessage(), 409);
        }
    }
}
