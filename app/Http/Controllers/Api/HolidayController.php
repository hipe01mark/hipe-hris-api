<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\HolidayService;
use Illuminate\Http\JsonResponse;

class HolidayController extends Controller
{
    public $holidayService;

    /**
     * Constructor
     */
    public function __construct(HolidayService $holidayService)
    {
        $this->holidayService = $holidayService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return responder()->success(
            $this->holidayService->getHolidays()
        )->respond();
    }
}
