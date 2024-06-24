<?php

namespace App\Services;

use App\Events\EmailVerificationConfirmed;
use App\Repositories\Interfaces\IUserRepository;
use Illuminate\Auth\Events\Verified;

class UserService
{
    public $userRepository;

    /**
     * Constructor
     */
    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Verify email
     * 
     * NOTE: Fix response (to follow)
     */
    public function verifyEmail(int $userId): string
    {
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            return 'User not found.';
        }

        if ($user->hasVerifiedEmail()) {
            return 'Your email address is already verified. Go back to app';
        }

        if ($this->userRepository->isTokenExpired()) {
            return 'Your email verification is already expired. Please resend a new email verification using the app!';
        }

        if ($user->markEmailAsVerified()) {
            $email = $user->email;
            event(new EmailVerificationConfirmed($email));
            event(new Verified($user));

            return 'Your email address has been verified. Go back to app';
        }

        return 'Something went wrong during email verification.';
    }
}
