<?php

namespace App\Repositories;

use App\Models\Holiday;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\Interfaces\IHolidayRepository;
use Illuminate\Database\Eloquent\Collection;

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

    /**
     * Get holidays filtered by date range.
     */
    public function getByDate(array $filters): Collection
    {
        return $this->model
            ->where(function($query) use ($filters) {
                if ($filters['start_date'] && $filters['end_date']) {
                    $query->whereBetween('start_date', [
                        $filters['start_date'], 
                        $filters['end_date']
                    ]);
                }
            })
            ->get();
    }
}
