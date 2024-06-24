<?php

namespace App\Repositories;

use App\Models\PasswordReset;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\IPasswordResetRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class PasswordResetRepository extends BaseRepository implements IPasswordResetRepository
{
    public $model;

    const ONE_HOUR = 1;

    /**
     * constructor
     */
    public function __construct(PasswordReset $model)
    {
        $this->model = $model;
    }

    /**
     * Check if password reset token is still valid or not.
     */
    public function isResetTokenValid(string $email, string $token): bool
    {
        $passwordResetData = $this->model->where('email', $email)
            ->where('created_at', '>', Carbon::now()->subHours(self::ONE_HOUR))
            ->first();
            
        return ($token && Hash::check($token, $passwordResetData->token))
            ? true : false;
    }
}
