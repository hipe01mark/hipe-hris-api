<?php

namespace App\Services;

use App\Models\User;
use App\Constants\Roles;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Hash;
use App\Events\EmailVerificationConfirmed;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Interfaces\IUserRepository;

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
    public function verifyEmail(int $userId, bool $markAsVerified = false): string
    {
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            return 'User not found.';
        }
        
        if ($this->userRepository->isTokenExpired()) {
            return 'Your email verification has expired. Please resend a new email verification using the app!';
        }

        if ($user->hasVerifiedEmail()) {
            return 'Your email address is already verified. Go back to app';
        }

        if ($markAsVerified === true) {;
            $user->markEmailAsVerified();
            event(new EmailVerificationConfirmed($user));
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
            ->model
            ->firstOrCreate($conditionData, $data);

        $user->assignRole($userInformation['role_id'] ?? Roles::USER);
        return $user;
    }

    /**
     * Get list of user list.
     */
    public function getList(): LengthAwarePaginator
    {
        return $this->userRepository
            ->getList();
    }

    /**
     * Delete a user by ID.
     */
    public function deleteById(int $id): bool
    {
        return $this->userRepository->deleteById($id);
    }
}
