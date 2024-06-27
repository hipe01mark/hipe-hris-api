<?php
namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface IUserRepository extends IBaseRepository 
{
    public function isTokenExpired(): bool;
    public function getByDate(array $filters): Collection;
}
