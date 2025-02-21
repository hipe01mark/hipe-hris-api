<?php
namespace App\Repositories\Interfaces;

use App\Models\UserLeave;
use App\Repositories\Interfaces\IBaseRepository;
use Illuminate\Database\Eloquent\Collection;

interface IUserLeaveRepository extends IBaseRepository 
{
    public function getByDate(array $filters): Collection;
    public function changeStatus(int $authUserId, int $leaveId, int $status): UserLeave;
}
