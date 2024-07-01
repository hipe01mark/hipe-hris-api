<?php
namespace App\Repositories\Interfaces;

use App\Repositories\Interfaces\IBaseRepository;
use Illuminate\Database\Eloquent\Collection;

interface IHolidayRepository extends IBaseRepository
{
    public function getByDate(array $filters): Collection;
}
