<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
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
    
    /**
     * Get list of user with attendance and date pagination
     */
    public function getByDate(array $filters): Collection
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

    /**
     * Get list of user information.
     */
    public function getList(): LengthAwarePaginator 
    {
        $columns = ['*'];
        $page = Request::input('page', 1);
        $limit = Request::input('limit', 6);
        $search = Request::input('search', '');
        $branch_id = Request::input('branch_id', []);

        return $this->model
            ->with([
                'roles.permissions', 
                'information.department', 
                'information.position',
                'information.branch',
                'information.status',
                'information.addresses'
            ])
            ->where(function ($query) use ($search) {
                if ($search) {
                    $query->orWhereHas('information', function ($query) use ($search) {
                        $query->where('first_name', 'LIKE', '%' . $search . '%')
                              ->orWhere('last_name', 'LIKE', '%' . $search . '%');
                    });
                }
            })
            ->where(function ($query) use ($branch_id) {
                if (!empty($branch_id)) {
                    $query->whereHas('information.branch', function ($query) use ($branch_id) {
                        $query->where('branch_id', $branch_id);
                    });
                }
            })
            ->limit($limit)
            ->latest()
            ->paginate($limit, $columns, 'page', $page);
    }
}
