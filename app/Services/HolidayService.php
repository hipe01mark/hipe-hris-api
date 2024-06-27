<?php
namespace App\Services;

use App\Repositories\Interfaces\IHolidayRepository;

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
    public function getHolidays() 
    {
        return $this->holidayRepository->all();
    }
}
