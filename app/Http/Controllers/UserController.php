<?php

namespace App\Http\Controllers;

use App\Events\UserBanned;
use App\Repositories\UserDataRepository;
use App\Services\AdminCheckerService;
use App\Services\BanService;
use App\Services\ValidatorService;
use App\User;
use Illuminate\Http\Request;
use App\Services\JsonResponseService;
use App\Services\RedirectService;
use App\Exceptions\UserRoleException;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    private $userDataRepository;

    private $adminChecker;

    private $redirectService;

    private $jsonResponse;


    /**
     * UserController constructor.
     * @param UserDataRepository $userRepo
     * @param AdminCheckerService $adminChecker
     * @param RedirectService $redirectService
     * @param JsonResponseService $jsonResponseService
     */
    public function __construct(UserDataRepository $userRepo,
                                AdminCheckerService $adminChecker,
                                RedirectService $redirectService,
                                JsonResponseService $jsonResponseService)
    {
        $this->middleware(['auth', 'revalidate']);
        $this->userDataRepository = $userRepo;
        $this->adminChecker = $adminChecker;
        $this->redirectService = $redirectService;
        $this->jsonResponse = $jsonResponseService;
    }


    /**
     * if request user is admin, return json response with misc user's data
     *
     * @param User $user
     * @param Request $request
     * @return mixed
     */
    public function showUser(Request $request, User $user)
    {
        try {
            $this->adminChecker->isAdmin($request->user());

            return $this->jsonResponse->okResponse(
                $this->userDataRepository->getUserData($user)
            );
        } catch (UserRoleException $roleException) {
            return $this->jsonResponse
                ->exceptionResponse($roleException
                    ->getMessage(), 409);
        }
    }


    /**
     * update user's account
     *
     * @param Request $request
     * @param ValidatorService $validator
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request,
                           ValidatorService $validator,
                           User $user)
    {
        $validator->validateUserUpdateRequest($request->all(), $user);

        $this->userDataRepository->updateUserData($user, $request);
        Log::notice('User account was updated',['id' => $user->id]);

        return $this->redirectService->redirectWithFlash(
            $request, 'index', 'status', 'Account was successfully updated'
        );
    }


    /**
     * ban user
     *
     * @param User $user
     * @param Request $request
     * @param BanService $banService
     * @return mixed
     */
    public function ban(User $user,
                        Request $request,
                        BanService $banService)
    {
        try {
            $this->adminChecker->isAdmin($request->user());
            $this->adminChecker->isUser($user);

            $banService->banUser($user);
            event(new UserBanned($user));

            return $this->jsonResponse->userBanResponse($user);

        } catch (UserRoleException $roleException) {
            return $this->jsonResponse
                ->exceptionResponse($roleException
                    ->getMessage(), 409);
        }
    }

}
