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
use App\Message;
use Illuminate\Http\Request;
use App\Repositories\UserDataRepository;
use App\services\AdminCheckerService;
use App\services\BanService;
use App\services\JsonResponseService;
use App\services\TimeHelperService;
use App\services\ValidatorService;

class CommentController extends Controller
{


    private $jsonResponse;


    private $timeHelper;


    private $adminChecker;


    /**
     * CommentController constructor.
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
     * Show message with comments
     *
     * @param CommentReader $commentReader
     * @param Message $message
     * @return mixed
     */
    public function read(CommentReader $commentReader, Message $message)
    {
        return $this->jsonResponse->okResponse(
            $commentReader->readPost($message)
        );
    }


    /**
     * Create comment
     *
     * @param CommentCreator $commentCreator
     * @param ValidatorService $validator
     * @param Message $message
     * @param Request $request
     * @return mixed
     */
    public function create(CommentCreator $commentCreator,
                           ValidatorService $validator,
                           Message $message,
                           Request $request)
    {
        $validator->ValidateComment($request->all());

        $comment = $commentCreator->createPost($request, $message);

        event(new CommentCreated($comment));

        return $this->jsonResponse->createdResponse($comment);
    }


    /**
     * update comment
     *
     * @param CommentUpdater $commentUpdater
     * @param ValidatorService $validator
     * @param Request $request
     * @param Message $message
     * @param Comment $comment
     * @return mixed
     */
    public function update(CommentUpdater $commentUpdater,
                           ValidatorService $validator,
                           Request $request,
                           Message $message,
                           Comment $comment)
    {
        $this->authorize('updateDeleteComment', $comment);
        if ($this->timeHelper->lessThanTwoMinutes($comment->created_at)) {

            $validator->ValidateComment($request->all());

            $newComment = $commentUpdater->updatePost($request, $comment);

            event(new CommentUpdated($newComment));

            return $this->jsonResponse->okResponse($newComment);
        } else {
            return $this->jsonResponse->timeExpiredResponse();
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

        if ($this->timeHelper->lessThanTwoMinutes($comment->created_at)) {

            event(new CommentDeleted($comment));

            $id = $commentDeleter->deletePost($comment);

            return $this->jsonResponse->okResponse("message : Comment $id was deleted");
        } else {
            return $this->jsonResponse->timeExpiredResponse();
        }
    }


    /**
     * Comment ban
     *
     * @param UserDataRepository $userDataRepository
     * @param BanService $banService
     * @param Request $request
     * @param Message $message
     * @param Comment $comment
     * @return mixed
     */
    public function ban(UserDataRepository $userDataRepository,
                        BanService $banService,
                        Request $request,
                        Message $message,
                        Comment $comment)
    {
        $commentUser = $userDataRepository->getUserById($comment->user_id);

        if ($this->adminChecker->isAdmin($request->user()) &&
            !$this->adminChecker->isAdmin($commentUser)) {

            $banService->banComment($comment);

            event(new CommentBanned($comment));

            return $this->jsonResponse->okResponse(
                "message : Tweet $comment->id was banned"
            );
        } else {
            return $this->jsonResponse->unauthorizedResponse();
        }

    }
}
