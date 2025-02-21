<?php

namespace App\Http\Controllers\Api\Auth;

use App\Constants\Define\HttpCode;
use App\Constants\Define\HttpStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Services\AuthService;
use App\Traits\HandlesTransactionTrait;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    use HandlesTransactionTrait;

    public $authService;
    public $client;
    
    CONST PASSWORD_GRANT_CLIENT_ID = 2;
    CONST GRANT_TYPE = 'password';

    /**
     * AuthController constructor
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Get the authenticated User.
     */
    public function me(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $authUser = $user->load([
            'roles.permissions', 
            'information.department', 
            'information.position',
            'information.branch',
        ]);

        return responder()->success($authUser)->respond();
    }

    /**
     * Authenticate user.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        return $this->runInTransaction(function () use ($request) {
            $credentials = $request->only('email', 'password');
            $isTooManyFailedAttempts = $this->authService
                ->isTooManyFailedAttempts();
            
            if (!$this->authService->attemptLogin($credentials) || $isTooManyFailedAttempts) {
                $this->authService->recordFailedAttempt();

                $code = $isTooManyFailedAttempts ? 
                    HttpCode::EXCEED_LOGIN_ATTEMPTS : HttpCode::INVALID_LOGIN;

                return responder()
                    ->error($code)
                    ->respond(HttpStatus::MISDIRECTED_REQUEST);
            }

            $this->authService->clearFailedAttempts();
            $token = $this->authService->generateAuthToken();
            return responder()
                ->success(['token' => $token])
                ->respond();
        });
    }

    /**
     * Get the authenticated User.
     */
    public function logout(): JsonResponse
    {
        $this->authService->revokeToken();

        return responder()->success([
            'message' => trans('auth.logout')
        ])->respond();
    }
}
