<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\IUserRepository;
use Illuminate\Database\Eloquent\Collection;

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
    
    /**
     * Get list of user with attendance and date pagination
     */
    public function getAttendancesByDate(array $filters): Collection
    {
        return $this->model
            ->with(['information', 'attendances' => function ($query) use ($filters) {
                $query->where(function($query) use ($filters) {
                    if ($filters['start_date'] && $filters['end_date']) {
                        $query->whereBetween('date', [
                            $filters['start_date'], 
                            $filters['end_date']
                        ]);
                    }
                });
            }])
            ->where(function ($query) use ($filters) {
                if ($filters['branch_id']) {
                    $query->whereHas('information.branch', function ($query) use ($filters) {
                        $query->where('branch_id', $filters['branch_id']);
                    });
                }

                if ($filters['department_id']) {
                    $query ->whereHas('information.department', function ($query) use ($filters) {
                        $query->where('department_id', $filters['department_id']);
                    });
                }
            })
            ->get();
    }
}
