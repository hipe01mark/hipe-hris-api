<?php

namespace App\Repositories;

use App\Models\Holiday;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\Interfaces\IHolidayRepository;

class HolidayRepository extends BaseRepository implements IHolidayRepository
{
    public $model;

    /**
     * constructor
     * 
     * @param Model $model
     */
    public function __construct(Holiday $model)
    {
        $this->model = $model;
    }
}
