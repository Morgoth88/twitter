<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Events\CommentBanned;
use App\Events\CommentDeleted;
use App\Events\CommentUpdated;
use App\Events\CommentCreated;
use App\Message;
use App\Repositories\CommentDataRepository;
use Illuminate\Http\Request;
use App\Interfaces\CommentInterface;
use App\TimeHelper;
use App\User;
use App\Ban;
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
use App\services\ValidatorService;

class CommentController extends Controller
{

    private $commentRepo;


    private $jsonResponseService;


    private $validatorService;


    private $timeHelper;


    private $userRepo;


    private $banService;


    private $adminChecker;


    /**
     * CommentController constructor.
     * @param CommentDataRepository $commentDataRepository
     * @param JsonResponseService $jsonResponseService
     * @param ValidatorService $validatorService
     * @param TimeHelperService $timeHelperService
     * @param UserDataRepository $userDataRepository
     * @param BanService $banService
     * @param AdminCheckerService $adminCheckerService
     */
    public function __construct(CommentDataRepository $commentDataRepository,
                                JsonResponseService $jsonResponseService,
                                ValidatorService $validatorService,
                                TimeHelperService $timeHelperService,
                                UserDataRepository $userDataRepository,
                                BanService $banService,
                                AdminCheckerService $adminCheckerService)
    {
        $this->middleware('auth');
        $this->commentRepo = $commentDataRepository;
        $this->jsonResponseService = $jsonResponseService;
        $this->validatorService = $validatorService;
        $this->timeHelper = $timeHelperService;
        $this->userRepo = $userDataRepository;
        $this->banService = $banService;
        $this->adminChecker = $adminCheckerService;
    }


    /**
     * Show message with comments
     *
     * @param Message $message
     * @return $this
     */
    public function read(Message $message)
    {
        return $this->jsonResponseService->okResponse(
            $this->commentRepo->getAllPosts($message)
        );
    }


    /**
     * Create comment
     *
     * @param Message $message
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create(Message $message, Request $request)
    {
        $this->validate($request, [
            'comment' => 'required|string'],
            ['Comment is empty!']);

        $comment = $this->commentRepo->createPost($request, $message);

        event(new CommentCreated($comment, $comment->user));

        return $this->jsonResponseService->createdResponse($comment);
    }


    /**
     * update comment
     *
     * @param Request $request
     * @param Message $message
     * @param Comment $comment
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, Message $message, Comment $comment)
    {
        $this->authorize('updateDeleteComment', $comment);
        if ($this->timeHelper->lessThanTwoMinutes($comment->created_at)) {

            $this->validate($request, [
                'comment' => 'required|string'],
                ['Comment is empty!']);

            $newComment = $this->commentRepo->updatePost($request, $comment);

            event(new CommentUpdated($newComment, $newComment->user));

            return $this->jsonResponseService->okResponse($newComment);
        } else {
            return $this->jsonResponseService->timeExpiredResponse();
        }
    }


    /**
     * delete comment
     *
     * @param Request $request
     * @param Message $message
     * @param Comment $comment
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete(Request $request, Message $message, Comment $comment)
    {
        $this->authorize('updateDeleteComment', $comment);

        if ($this->timeHelper->lessThanTwoMinutes($comment->created_at)) {

            $commentCount = $message->comment()->where('old', '0')->count();

            event(new CommentDeleted($comment));

            $id = $comment->id;
            $comment->delete();

            return $this->jsonResponseService->okResponse("message : Comment $id was deleted");
        } else {
            return $this->jsonResponseService->timeExpiredResponse();
        }
    }


    /**
     * Comment ban
     *
     * @param Request $request
     * @param Message $message
     * @param Comment $comment
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function ban(Request $request, Message $message, Comment $comment)
    {
        $user = $this->userRepo->getUserById($comment->user_id);

        if ($this->adminChecker->isAdmin($request->user()) &&
            !$this->adminChecker->isAdmin($user)) {

            $this->banService->banComment($comment);

            event(new CommentBanned($comment));

            return $this->jsonResponseService->okResponse(
                "message : Tweet $comment->id was banned"
            );
        } else {
            return $this->jsonResponseService->unauthorizedResponse();
        }

    }
}
