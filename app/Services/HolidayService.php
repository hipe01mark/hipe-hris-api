<?php
namespace App\Services;

use App\Repositories\Interfaces\IHolidayRepository;
use Illuminate\Database\Eloquent\Collection;

class HolidayService 
{
    public $holidayRepository;
    
    /**
     * Constructor
     */
    public function __construct(IHolidayRepository $iholidayRepository)
    {
        $this->holidayRepository = $iholidayRepository;
    }

    /**
     * Get list of holidays
     */
    public function getHolidays(): Collection
    {
        $filters = [
            'start_date' => request()['start_date'] ?? null,
            'end_date' => request()['end_date'] ?? null
        ];

        return $this->holidayRepository->getByDate($filters);
    }
}
