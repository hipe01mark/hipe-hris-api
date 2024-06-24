<?php

namespace App\Repositories;

use App\Models\UserAttendance;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\IUserAttendanceRepository;

class UserAttendanceRepository extends BaseRepository implements IUserAttendanceRepository
{
    public $model;

    /**
     * Constructor
     */
    public function __construct(UserAttendance $model)
    {
        $this->model = $model;
    }

    /**
     * Get the user's attendance record for today.
     */
    public function getAttendanceByDate(string $date, int $userId): ?UserAttendance
    {
        return $this->model
            ->where('date', $date)
            ->where('user_id', $userId)
            ->first();
    }
}
