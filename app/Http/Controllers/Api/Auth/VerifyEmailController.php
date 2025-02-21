<?php

namespace App\Http\Controllers\Api\Auth;


use App\Services\AuthService;
use App\Services\UserService;
use App\Traits\HandlesTransactionTrait;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VerifyEmailController extends Controller
{
    public $authService;
    public $userService;

    use HandlesTransactionTrait;

    /**
     * Constructor
     */
    public function __construct(AuthService $authService, UserService $userService)
    {
        $this->authService = $authService;
        $this->userService = $userService;
    }

    /**
     * Resending email verification
     * notification via email.
     */
    public function resend(Request $request): JsonResponse
    {
        return $this->runWithoutTransaction(function() use ($request) {
            $user = auth()->user();
            $message = $this->userService->verifyEmail($user->id);
            $request->user()->sendEmailVerificationNotification();
            
            return responder()
                ->success(['message' => $message])
                ->respond();
        });
    }


    /**
     * Verifying user account.
     */
    public function verify(Request $request): JsonResponse
    {
        return $this->runWithoutTransaction(function() use ($request) {
            $userId = $request->route('id');
            $message = $this->userService->verifyEmail($userId, true);

            return responder()
                ->success(['message' => $message])
                ->respond();
        });
    }
}
