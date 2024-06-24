<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\IUserRepository;

class UserRepository extends BaseRepository implements IUserRepository
{
    public $model;

    /**
     * Constructor
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Check if token is expired
     */
    public function isTokenExpired(): bool
    {
        $tokenExpired = 
            $this->model->email_verified_at !== null && 
            $this->model->email_verified_at
                 ->addMinutes(config('auth.verification.expire', 60))
                 ->isPast();
                 
        return $tokenExpired;
    }
}
