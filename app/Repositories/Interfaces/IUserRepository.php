<?php
namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface IUserRepository extends IBaseRepository 
{
    public function isTokenExpired(): bool;
    public function getAttendancesByDate(array $filters): Collection;
}
