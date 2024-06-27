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
        return $this->holidayRepository->all();
    }
}
