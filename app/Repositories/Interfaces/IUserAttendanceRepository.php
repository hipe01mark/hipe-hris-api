<?php
namespace App\Repositories\Interfaces;

use App\Models\UserAttendance;
use App\Repositories\Interfaces\IBaseRepository;

interface IUserAttendanceRepository extends IBaseRepository 
{
    public function getAttendanceByDate(string $date, int $userId): ?UserAttendance;
}
