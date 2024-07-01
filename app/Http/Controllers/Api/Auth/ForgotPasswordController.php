<?php
namespace App\Http\Controllers\Api\Auth;

use App\Constants\Define\HttpCode;
use App\Constants\Define\HttpStatus;
use Illuminate\Routing\Controller;
use App\Events\ResetPasswordConfirmed;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Api\Auth\ForgotPasswordRequest;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use App\Repositories\Interfaces\IPasswordResetRepository;
use App\Services\AuthService;
use App\Traits\HandlesTransactionTrait;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    use HandlesTransactionTrait;

    CONST FORGOT_PASSWORD_PATH = '/forgot/password/reset';

    public $authService;
    public $passwordResetRepository;

    /**
     * Constructor
     */
    public function __construct(
        AuthService $authService, 
        IPasswordResetRepository $passwordResetRepository
    )
    {
        $this->authService = $authService;
        $this->passwordResetRepository = $passwordResetRepository;
    }

    /**
     * Get reset link token.
     * 
     * TODO: Need to fix the return response.
     */
    public function tokenResetLink(ForgotPasswordRequest $request, bool $mobile = true): string
    {
        $email = $request['email']; 
        $token = $request['token'];

        if ($this->passwordResetRepository->isResetTokenValid($email, $token) == true) {
            $path = self::FORGOT_PASSWORD_PATH;

            if (!$mobile) {
                $frontEndUrl = env('APP_FRONT_END_URL');
                $concatenatedLink = $frontEndUrl.$path.'?token='.$token.'&email='.$email;
                return redirect()->away($concatenatedLink);
            }

            event(new ResetPasswordConfirmed($token, $email));
            return 'Go back to the app';
        }

        return 'Your password reset link is already expired';
    }

    /**
     * Reset password using em,ail & token.
     */
    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        return $this->runInTransaction(function () use ($request) {
            $reset = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->setRememberToken(Str::random(60));
                    $user->save();
    
                    event(new PasswordReset($user));
                    event(new Verified($user));
                    $this->authService->clearFailedAttempts();
                }
            );
            
            if ($reset === Password::INVALID_TOKEN) {
                return responder()
                    ->error(HttpCode::INVALID_PASSWORD_RESET_TOKEN)
                    ->respond(HttpStatus::UNAUTHORIZED);
            }

            return responder()
                ->success(['message' => trans('passwords.reset')])
                ->respond();
        });
    }

    /**
     * Forgot password of the user.
     */
    public function forgot(ForgotPasswordRequest $request): JsonResponse
    {
        return $this->runInTransaction(function () use ($request) {
            Password::sendResetLink($request->only('email'));

            return responder()
                ->success(['message' => trans('passwords.sent')])
                ->respond();
        });
    }
}
