<?php
namespace App\Services;

use App\Constants\Locations;
use App\Repositories\Interfaces\IUserRepository;
use App\Models\UserAttendance;
use App\Repositories\Interfaces\IUserAttendanceRepository;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
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
    public function getAttendancesByDate(array $filters): Collection
    {
        $filters = [
            'start_date' => $filters['start_date'] ?? null,
            'end_date' => $filters['end_date'] ?? null,
            'branch_id' => $filters['branch_id'] ?? null,
            'department_id' => $filters['department_id'] ?? null
        ];

        $listOfUsersWithAttendances = $this->userRepository
            ->getAttendancesByDate($filters);

        return $listOfUsersWithAttendances;
    }

    /**
     * Save user attendance.
     */
    public function save(int $userId, array $attendance): UserAttendance
    {
        $conditionData = [
            'id' => $attendance['id'] ?? null,
            'user_id' => $userId,
            'date' => $attendance['date'],
        ];

        $data = [
            'user_id' => $userId,
            'date' => $attendance['date'],
            'time_in' => $attendance['time_in'],
            'time_out' => $attendance['time_out'] ?? null,
            'state' => $attendance['state'] ?? null,
            'location' => $attendance['location']
        ];
        
        return $this->userAttendanceRepository
            ->updateOrCreate($conditionData, $data);
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
     * Save time in of the user.
     */
    public function timeInWFH(int $userId): UserAttendance
    {
        $attendanceRequest = [
            'user_id' => $userId,
            'date' => Carbon::now(),
            'time_in' => Carbon::now(),
            'location' => Locations::WFH
        ];

        return $this->userAttendanceRepository->create($attendanceRequest);
    }

    /**
     * Save time out of the user based on today's date and time in of the user.
     */
    public function timeOutWFH(int $userId): UserAttendance
    {
        $todayAttendance = $this->getTodayAttendance($userId);

        $attendanceRequest = [
            'user_id' => $userId,
            'date' => Carbon::now(),
            'time_out' => Carbon::now(),
            'location' => Locations::WFH
        ];

        return $this->userAttendanceRepository->updateOrCreate(
            ['id' => $todayAttendance->id ?? null],
            $attendanceRequest
        );
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
