<?php
namespace App\Repositories\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Interfaces\IBaseRepository;

interface IUserInformationRepository extends IBaseRepository 
{
    public function getList(int $limit, string $search, int $page): LengthAwarePaginator;
}
