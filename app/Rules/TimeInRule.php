<?php

namespace App\Rules;

use App\Services\UserAttendanceService;
use Illuminate\Contracts\Validation\Rule;

class TimeInRule implements Rule
{
    protected $userAttendanceService;

    /**
     * Create a new rule instance.
     */
    public function __construct(UserAttendanceService $userAttendanceService)
    {
        $this->userAttendanceService = $userAttendanceService;
    }

    /**
     * Determine if the validation rule passes.
     */
    public function passes($attribute, $value): bool
    {
        $userId = auth()->user()->id;
        $todayAttendance = $this->userAttendanceService
            ->getTodayAttendance($userId);

        $timeIn = $todayAttendance['time_in'] ?? null;

        return $timeIn ? false : true;
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return "The user has already timed in";
    }
}
