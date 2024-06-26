<?php

namespace App\Services;

use App\Constants\Roles;
use App\Events\EmailVerificationConfirmed;
use App\Models\User;
use App\Repositories\Interfaces\IUserRepository;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Hash;

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

    /**
     * Update or create user.
     */
    public function save(int $userId = null, array $userInformation): User
    {
        $conditionData = ['id' => $userId];
        $data = [
            'temporary_name' => $userInformation['temporary_name'] ?? null,
            'email' => $userInformation['email']
        ];

        if (isset($userInformation['password'])) {
            $data['password'] = Hash::make($userInformation['password']);
        }

        $user = $this
            ->userRepository
            ->updateOrCreate($conditionData, $data);

        $user->assignRole($userInformation['role_id'] ?? Roles::USER);
        return $user;
    }
}
