<?php
namespace App\Services;

use App\Repositories\Interfaces\IUserRepository;
use App\Models\UserAttendance;
use App\Repositories\Interfaces\IUserAttendanceRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class UserAttendanceService 
{
    public $userRepository;
    public $userAttendanceRepository;
    
    /**
     * User attendance service constructor
     */
    public function __construct(
        IUserRepository $iUserRepository,
        IUserAttendanceRepository $iUserAttendanceRepository
    )
    {
        $this->userRepository = $iUserRepository;
        $this->userAttendanceRepository = $iUserAttendanceRepository;
    }

    /**
     * Get list of user with attendance and date pagination
     */
    public function getByDate(array $filters): Collection
    {
        $filters = [
            'start_date' => $filters['start_date'] ?? null,
            'end_date' => $filters['end_date'] ?? null,
            'branch_id' => $filters['branch_id'] ?? null,
            'department_id' => $filters['department_id'] ?? null
        ];

        $listOfUsersWithAttendances = $this->userRepository
            ->getByDate($filters);

        return $listOfUsersWithAttendances;
    }

    /**
     * Save time out of the user based on today's
     * date and time in of the user.
     */
    public function timeLog(int $userId, int $location, bool $isTimeOut = false): UserAttendance
    {
        $todayAttendance = $this->getTodayAttendance($userId);

        $conditionData = [
            'id' => $todayAttendance->id ?? null
        ];
        
        $attendanceRequest = [
            'user_id' => $userId,
            'date' => Carbon::now(),
        ];
        
        if ($isTimeOut) {
            $attendanceRequest['time_out'] = Carbon::now(); 
            $attendanceRequest['out_location'] = $location; 
        } else if(!$isTimeOut) {
            $attendanceRequest['time_in'] = Carbon::now(); 
            $attendanceRequest['in_location'] = $location; 
        }

        return $this->userAttendanceRepository
            ->updateOrCreate($conditionData, $attendanceRequest);
    }

    /** 
     * Get attendance by ID
     */
    public function getAttendanceLogByID(int $attendanceId): UserAttendance
    {
        return $this->userAttendanceRepository
            ->findById($attendanceId);
    }

    /**
     * Get the auth user's attendance record for today.
     */
    public function getTodayAttendance(int $userId): ?UserAttendance
    {
        $today = Carbon::today()->format('Y-m-d');
        return $this->userAttendanceRepository
            ->getAttendanceByDate($today, $userId);
    }
}
