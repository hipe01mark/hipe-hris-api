<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\UserLeave;
use App\Repositories\Interfaces\IUserLeaveRepository;
use Illuminate\Database\Eloquent\Collection;

class UserLeaveRepository extends BaseRepository implements IUserLeaveRepository
{
    public $model;

    /**
     * Constructor
     */
    public function __construct(UserLeave $model)
    {
        $this->model = $model;
    }

    /**
     * Get leaves filtered by date range.
     */
    public function getByDate(array $filters): Collection
    {
        return $this->model
            ->with(['user.information.department', 'user.information.branch'])
            ->where(function($query) use ($filters) {
                if ($filters['start_date'] && $filters['end_date']) {
                    $query->whereBetween('start_date', [
                        $filters['start_date'], 
                        $filters['end_date']
                    ]);
                }
            })
            ->where(function ($query) use ($filters) {
                if ($filters['branch_id']) {
                    $query->whereHas('user.information.branch', function ($query) use ($filters) {
                        $query->where('branch_id', $filters['branch_id']);
                    });
                }
                if ($filters['department_id']) {
                    $query->whereHas('user.information.department', function ($query) use ($filters) {
                        $query->where('department_id', $filters['department_id']);
                    });
                }
            })
            ->get();
    }

    /**
     * Change leave Status
     */
    public function changeStatus(int $authUserId, int $leaveId, int $status): UserLeave
    {
        $leave = $this->findById($leaveId);
        $leave->status = $status;
        $leave->approver_id = $authUserId;
        $leave->save();

        return $leave;
    }
}
