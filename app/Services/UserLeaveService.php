<?php
namespace App\Services;

use App\Models\UserLeave;
use App\Repositories\Interfaces\IUserLeaveRepository;
use Illuminate\Database\Eloquent\Collection;

class UserLeaveService 
{
    public $userLeaveRepository;
    
    /**
     * User leave service constructor
     */
    public function __construct(IUserLeaveRepository $iUserLeaveRepository)
    {
        $this->userLeaveRepository = $iUserLeaveRepository;
    }

    /**
     * Get list of user leaves by date pagination.
     */
    public function getLeavesByDate(array $request): Collection
    {
        $filters = [
            'start_date' => $request['start_date'] ?? null,
            'end_date' => $request['end_date'] ?? null,
            'branch_id' => $request['branch_id'] ?? null,
            'department_id' => $request['department_id'] ?? null
        ];

        return $this->userLeaveRepository
            ->getByDate($filters);
    }

    /**
     * Save user leave.
     */
    public function save(int $userId, array $leave, int $userLeaveId = null): UserLeave
    {
        $conditionData = [
            'id' => $userLeaveId
        ];

        $data = [
            'user_id' => $userId,
            'start_date' => $leave['start_date'],
            'end_date' => $leave['half_day'] ? $leave['start_date'] : $leave['end_date'],
            'reason' => $leave['reason'],
            'initial_approver' => $leave['initial_approver'],
            'type' => $leave['type'],
            'half_day' => $leave['half_day'],
            'post_meridiem' => $leave['post_meridiem']
        ];
        
        return $this->userLeaveRepository
            ->updateOrCreate($conditionData, $data)
            ->load(['user.information.department']);
    }

    /**
     * Change leave status of user leave
     */
    public function changeStatus(int $authUserId, int $leaveId, int $status): UserLeave
    {
        $leave =  $this->userLeaveRepository
            ->changeStatus($authUserId, $leaveId, $status);

        return $leave->load(['user.information.department']);
    }

    /**
     * Delete leave by Id
     */
    public function deleteLeave(int $leaveId): bool
    {
        return $this->userLeaveRepository
            ->deleteById($leaveId);
    }
}
