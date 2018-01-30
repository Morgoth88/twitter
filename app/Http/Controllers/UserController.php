<?php

namespace App\Http\Controllers;

use App\Events\UserBanned;
use App\Exceptions\ValidatorException;
use App\Repositories\UserDataRepository;
use App\services\AdminCheckerService;
use App\services\BanService;
use App\services\LogService;
use App\services\PasswordCheckerService;
use App\services\ValidatorService;
use App\User;
use Illuminate\Http\Request;
use App\services\JsonResponseService;
use App\services\RedirectService;

class UserController extends Controller
{


    private $userDataRepository;


    private $adminChecker;


    private $logService;


    private $redirectService;


    /**
     * UserController constructor.
     * @param UserDataRepository $userRepo
     * @param AdminCheckerService $adminChecker
     * @param LogService $logService
     * @param RedirectService $redirectService
     */
    public function __construct(UserDataRepository $userRepo,
                                AdminCheckerService $adminChecker,
                                LogService $logService,
                                RedirectService $redirectService)
    {
        $this->middleware('auth');
        $this->userDataRepository = $userRepo;
        $this->adminChecker = $adminChecker;
        $this->logService = $logService;
        $this->redirectService = $redirectService;
    }


    /**
     * if request user is admin, return json response with misc user's data
     *
     * @param User $user
     * @param JsonResponseService $jsonResponseService
     * @return mixed
     */
    public function showUser(User $user,
                             JsonResponseService $jsonResponseService)
    {
        if ($this->adminChecker->isAdmin($user)) {
            return $jsonResponseService->okResponse(
                $this->userDataRepository->getUserData($user)
            );
        } else {
            return $jsonResponseService->unauthorizedResponse();
        }
    }


    /**
     * update user's account
     *
     * @param Request $request
     * @param ValidatorService $validator
     * @param PasswordCheckerService $passwordChecker
     * @param JsonResponseService $jsonResponseService
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request,
                           ValidatorService $validator,
                           PasswordCheckerService $passwordChecker,
                           JsonResponseService $jsonResponseService)
    {
            $validator->validateUserUpdateRequest($request->all());
            $user = $this->userDataRepository->getUserById($request->user()->id);

            if ($passwordChecker->checkPasswords($request, $user)) {

                $this->userDataRepository->updateUserData($user, $request);
                $this->logService->log($request->user(), 'Account update');

                return $this->redirectService->redirectWithFlash(
                    $request, 'index', 'status', 'Account was successfully updated'
                );
            } else {
                return $this->redirectService->redirectWithFlash(
                    $request, 'accountUpdateForm', 'error', 'Passwords do not match'
                );
            }
    }


    /**
     * ban user
     *
     * @param User $user
     * @param Request $request
     * @param BanService $banService
     * @param JsonResponseService $jsonResponseService
     */
    public function ban(User $user, Request $request, BanService $banService,
                        JsonResponseService $jsonResponseService)
    {
        if ($this->adminChecker->isAdmin($request->user()) &&
            !$this->adminChecker->isAdmin($user)) {

            $banService->banUser($user);
            $this->logService->log($user, "User baned by {$request->user()->email}");
            event(new UserBanned($user));

            $jsonResponseService->userBanResponse($user);
        } else {
            $jsonResponseService->unauthorizedResponse();
        }
    }

}
